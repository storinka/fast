<?php

use Cycle\Annotated\Embeddings;
use Cycle\Annotated\Entities;
use Cycle\Annotated\MergeColumns;
use Cycle\Annotated\MergeIndexes;
use Cycle\Annotated\TableInheritance;
use Cycle\Database\Config\DatabaseConfig;
use Cycle\Database\Config\SQLite\FileConnectionConfig;
use Cycle\Database\Config\SQLite\MemoryConnectionConfig;
use Cycle\Database\Config\SQLiteDriverConfig;
use Cycle\Database\Database;
use Cycle\Database\DatabaseManager;
use Cycle\ORM\Factory;
use Cycle\ORM\ORM;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\Schema;
use Cycle\Schema\Compiler;
use Cycle\Schema\Generator\GenerateModifiers;
use Cycle\Schema\Generator\GenerateRelations;
use Cycle\Schema\Generator\GenerateTypecast;
use Cycle\Schema\Generator\RenderModifiers;
use Cycle\Schema\Generator\RenderRelations;
use Cycle\Schema\Generator\RenderTables;
use Cycle\Schema\Generator\ResetTables;
use Cycle\Schema\Generator\SyncTables;
use Cycle\Schema\Generator\ValidateEntities;
use Cycle\Schema\Registry;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Invoke\Container;
use Spiral\Tokenizer\ClassLocator;
use Symfony\Component\Finder\Finder;

$dbal = new DatabaseManager(
    new DatabaseConfig([
        'default' => 'default',
        'databases' => [
            'default' => ['connection' => 'sqlite']
        ],
        'connections' => [
            'sqlite' => new SQLiteDriverConfig(
                connection: new FileConnectionConfig(__DIR__ . '/db.sqlite'),
                queryCache: true,
            ),
        ]
    ])
);

$entitiesFinder = (new Finder())->files()->in([__DIR__ . '/../entities']);
$entitiesClassLocator = new ClassLocator($entitiesFinder);

AnnotationRegistry::registerLoader('class_exists');

$schema = (new Compiler())->compile(new Registry($dbal), [
    new ResetTables(),             // re-declared table schemas (remove columns)
    new Embeddings($entitiesClassLocator),        // register embeddable entities
    new Entities($entitiesClassLocator),          // register annotated entities
    new TableInheritance(),               // register STI/JTI
    new MergeColumns(),                   // add @Table column declarations
    new GenerateRelations(),       // generate entity relations
    new GenerateModifiers(),       // generate changes from schema modifiers
    new ValidateEntities(),        // make sure all entity schemas are correct
    new RenderTables(),            // declare table schemas
    new RenderRelations(),         // declare relation keys and indexes
    new RenderModifiers(),         // render all schema modifiers
    new MergeIndexes(),                   // add @Table column declarations
    new SyncTables(),              // sync table changes to database
    new GenerateTypecast(),        // typecast non string columns
]);

$orm = new ORM(new Factory($dbal), new Schema($schema));

Container::singleton(ORMInterface::class, $orm);
Container::singleton(DatabaseManager::class, $dbal);

function db(): Database
{
    global $dbal;
    return $dbal->getDatabases()[0];
}
