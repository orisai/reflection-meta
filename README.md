<h1 align="center">
	<img src="https://github.com/orisai/.github/blob/main/images/repo_title.png?raw=true" alt="Orisai"/>
	<br/>
	Reflection Meta
</h1>

<p align="center">
    PHP reflection in more reliable and deterministic way - for declarative engines
</p>

<p align="center">
	📄 Check out our <a href="docs/README.md">documentation</a>.
</p>

<p align="center">
	💸 If you like Orisai, please <a href="https://orisai.dev/sponsor">make a donation</a>. Thank you!
</p>

<p align="center">
	<a href="https://github.com/orisai/reflection-meta/actions?query=workflow%3ACI">
		<img src="https://github.com/orisai/reflection-meta/workflows/CI/badge.svg">
	</a>
	<a href="https://coveralls.io/r/orisai/reflection-meta">
		<img src="https://badgen.net/coveralls/c/github/orisai/reflection-meta/v1.x?cache=300">
	</a>
	<a href="https://dashboard.stryker-mutator.io/reports/github.com/orisai/reflection-meta/v1.x">
		<img src="https://badge.stryker-mutator.io/github.com/orisai/reflection-meta/v1.x">
	</a>
	<a href="https://packagist.org/packages/orisai/reflection-meta">
		<img src="https://badgen.net/packagist/dt/orisai/reflection-meta?cache=3600">
	</a>
	<a href="https://packagist.org/packages/orisai/reflection-meta">
		<img src="https://badgen.net/packagist/v/orisai/reflection-meta?cache=3600">
	</a>
	<a href="https://choosealicense.com/licenses/mpl-2.0/">
		<img src="https://badgen.net/badge/license/MPL-2.0/blue?cache=3600">
	</a>
<p>

##

```php
use Orisai\ReflectionMeta\Structure\StructureBuilder;
use Orisai\ReflectionMeta\Structure\StructureFlattener;
use Orisai\ReflectionMeta\Structure\StructureGrouper;
use ReflectionClass;

$reflector = new ReflectionClass(ExampleClass::class);
$hierarchy = StructureBuilder::build($reflector);
$list = StructureFlattener::flatten($hierarchy);
$group = StructureGrouper::group($list);

var_dump($group);
/*
StructureGroup(
	classes: [
		ClassStructure(ParentInterface),
		ClassStructure(ParentTrait),
		ClassStructure(ParentClass),
		ClassStructure(ExampleInterface),
		ClassStructure(ExampleTrait),
		ClassStructure(ExampleClass),
	],
	constants: [
		'::publicConstName' => [
			ConstantStructure(ExampleInterface, 'publicConstName'),
		],
		'::protectedConstName' => [
			ConstantStructure(ParentClass, 'protectedConstName'),
			ConstantStructure(ExampleClass, 'protectedConstName'),
		],
		'ParentClass::privateConstName' => [
			ConstantStructure(ParentClass, 'privateConstName'),
		],
	],
	properties: [
		'::publicPropertyName' => [
			PropertyStructure(ExampleClass, 'publicPropertyName'),
		],
		// ...
	],
	methods: [
		// ...
	],
)
*/
```
