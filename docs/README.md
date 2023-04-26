# Reflection Meta

PHP reflection in more reliable and deterministic way - for declarative engines

## Content

- [Setup](#setup)
- [What is it good for?](#what-is-it-good-for)
- [Building reflectors structure](#building-reflectors-structure)
	- [Hierarchy](#hierarchy)
	- [List](#list)
	- [Group](#group)
- [Reading metadata](#reading-metadata)
	- [Annotations](#annotations)
	- [Attributes](#attributes)
- [Callable definition and source definition](#callable-definition-and-source-definition)
- [What you should consider](#what-you-should-consider)

## Setup

Install with [Composer](https://getcomposer.org)

```sh
composer require orisai/reflection-meta
```

## What is it good for?

For declarative engines like [orisai/object-mapper](https://github.com/orisai/object-mapper), defining what they do in
form of annotations or attributes in a class (or in an external file). It makes PHP reflection easier to read and
distinguish between callable context definition (class using trait or implementing interface) and the actual source
definition (trait or interface).

This is an internal tool, and you likely do not need to use it directly.

## Building reflectors structure

To make PHP reflection usable for declarative engines we restructure reflectors in several phases:

- [Hierarchy](#hierarchy) - exact copy of classes structure as declared in code
- [List](#list) - flattened classes structure, removing duplicate interfaces and traits
- [Group](#group) - grouping overloaded definitions of public/protected definitions

```php
use Orisai\ReflectionMeta\Structure\StructureBuilder;
use Orisai\ReflectionMeta\Structure\StructureFlattener;
use Orisai\ReflectionMeta\Structure\StructureGrouper;
use ReflectionClass;

$reflector = new ReflectionClass(ExampleClass::class);
$hierarchy = StructureBuilder::build($reflector);
$list = StructureFlattener::flatten($hierarchy);
$group = StructureGrouper::group($list);
```

### Hierarchy

`HierarchyClassStructure` reflects the classes exactly how they were defined. Class with its parent, all interfaces and
traits. Inside each class definition (including interfaces, traits and enums which are considered special types of
classes by PHP reflection) are its constants, properties and methods.

Unlike in PHP reflection, public and protected definitions from parents are available only via parent definition, to
avoid duplicates.

```php
use Orisai\ReflectionMeta\Structure\StructureBuilder;
use ReflectionClass;

$reflector = new ReflectionClass(ExampleClass::class);
$hierarchy = StructureBuilder::build($reflector); // HierarchyClassStructure

$hierarchy->getParent(); // self|null
$hierarchy->getInterfaces(); // list<self>
$hierarchy->getTraits(); // list<self>

$hierarchy->getConstants(); // list<ConstantStructure>
$hierarchy->getProperties(); // list<PropertyStructure>
$hierarchy->getMethods(); // list<MethodStructure>

$hierarchy->getContextClass(); // ReflectionClass
$hierarchy->getSource(); // ClassSource
```

### List

`StructureList` changes the class hierarchy into a flat list in following order:

- parent
	- interfaces
	- traits
	- class
- interfaces
- traits
- class

In case any interface or trait is used twice (e.g. by parent and child class), the later duplicate is removed.

Constants, properties and methods from all the classes are flattened as well.

```php
use Orisai\ReflectionMeta\Structure\StructureFlattener;

$list = StructureFlattener::flatten($hierarchy); // StructureList

$list->getClasses(); // list<ClassStructure>
$list->getConstants(); // list<ConstantStructure>
$list->getProperties(); // list<PropertyStructure>
$list->getMethods(); // list<MethodStructure>
```

### Group

`StructureGroup` groups all matching definitions of the same constants, properties and methods.

All public and protected properties with the same name are grouped together in an array with key `::propertyName`.
Private properties are alone in their own group, with key `ClassName::propertyName`. Same applies to constants and
methods.

```php
use Orisai\ReflectionMeta\Structure\StructureGrouper;

$group = StructureGrouper::group($list); // StructureGroup

$group->getClasses(); // list<ClassStructure>
$group->getConstants(); // array<string, list<ConstantStructure>>
$group->getProperties(); // array<string, list<PropertyStructure>>
$group->getMethods(); // array<string, list<MethodStructure>>
```

## Reading metadata

To read metadata from a reflector (received in [building phase](#building-reflectors-structure)), use the `MetaReader`

- [annotations reader](#annotations)
- [attributes reader](#attributes)

```php
use Orisai\ReflectionMeta\Reader\AttributesMetaReader;

$attributeClass = ExampleAttribute::class;

$reader = new AttributesMetaReader(); // Or any other MetaReader
$reader->readClass($reflectionClass, $attributeClass); // list<$attributeClass>
$reader->readConstant($reflectionConstant, $attributeClass); // list<$attributeClass>
$reader->readProperty($reflectionProperty, $attributeClass); // list<$attributeClass>
$reader->readMethod($reflectionMethod, $attributeClass); // list<$attributeClass>
$reader->readParameter($reflectionParameter, $attributeClass); // list<$attributeClass>
```

e.g. loading metadata from [structure group](#group) classes would look like this:

```php
$metaByClass = [];
foreach ($group->getClasses() as $class) {
	$metaByClass[] = $reader->readClass($class, $attributeClass);
}

$meta = array_merge(...$metaByClass);
```

### Annotations

Load metadata from [doctrine/annotations](https://github.com/doctrine/annotations)

```php
use Doctrine\Common\Annotations\AnnotationReader;
use Orisai\ReflectionMeta\Reader\AnnotationsMetaReader;

$reader = new AnnotationsMetaReader();
// or with specific doctrine/annotations reader
$reader = new AnnotationsMetaReader(new AnnotationReader());
```

```php
/**
 * @Annotation
 */
class ExampleAnnotation {}

/**
 * @ExampleAnnotation()
 */
class ExampleClass {}
```

### Attributes

Load metadata from PHP (8.0+) attributes

```php
use Orisai\ReflectionMeta\Reader\AttributesMetaReader;

$reader = new AttributesMetaReader();
```

```php
use Attribute;

#[Attribute]
class ExampleAttribute {}

#[ExampleAttribute]
class ExampleClass {}
```

## Callable definition and source definition

Each structure has two ways of accessing reflection.

First is `$structure->getContextClass()`. It returns the class which can be used to access properties and methods by
reflection or by closure binding and therefore excludes interfaces and traits.

To make it work, the reflector given to `StructureBuilder::build()` must be a non-abstract class. Otherwise, context
will be based on whatever reflector was given to builder. In case of abstract classes, interfaces and traits, the object
will be not instantiable and neither non-static nor static properties and methods can be called.

For example assigning a property of any visibility to an instance would look like this:

```php
$object = /* create instance of a root class (the one given to StructureBuilder */;

$contextClassName = $propertyStructure->getContextClass()->getName();
$propertyName = $propertyStructure->getSource()->getReflector()->getName();
$value = 'anything';

(fn () => $object->$propertyName = $value)
			->bindTo($object, $contextClassName)();
```

Seconds way of accessing reflection is `$structure->getSource()->getReflector()`. It returns the actual source as
defined in code, including interfaces and traits and therefore is useful to show in metadata validation errors.

Each source is an instance of `ReflectorSource` and their description is available
in [orisai/source-map](https://github.com/orisai/source-map)

For each structure, different source is returned:

- `ClassStructure` -> `ClassSource`
	- `HierarchyClassStructure` -> `ClassSource`
- `ConstantStructure` -> `ClassConstantSource`
- `MethodStructure` -> `MethodSource`
- `ParameterStructure` -> `ParameterSource`
- `PropertyStructure` -> `PropertySource`

## What you should consider

These are just tips what you should consider in your own code when using this library as there is no general solution
provided by us

- Visibility - With [Closure::bindTo()](https://www.php.net/manual/en/closure.bindto.php)
  and `$structure->getContextClass()` you can work with properties and methods with any visibility, but you may still
  want to choose whether public, protected and private should be all supported.
- Static - With [Closure::bindTo()](https://www.php.net/manual/en/closure.bindto.php)
  and `$structure->getContextClass()` you can work with properties and methods that are either static or non-static, but
  you will likely want to support only non-static properties and may want to support only non-static methods.
- Source - Metadata can be defined on class or its subtypes - interface, trait or enum.
	- Check whether they are defined only by types that you want to support.
	- You will probably want to require non-abstract class as a root source and forbid enums for most cases.
- Target - Metadata can be defined on class (or its subtypes), constant, property, method or method parameter.
	- Both [annotations](#annotations) and [attributes](#attributes) can individually define their allowed target
		- Alternatively (or in addition) you may also want to check all of them
	- Method parameters are not supported by [annotations](#annotations), only [attributes](#attributes)
