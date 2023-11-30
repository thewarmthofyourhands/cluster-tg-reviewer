<?php

declare(strict_types=1);

namespace App\Validation;

use Eva\Filesystem\Filesystem;

readonly class JsonSchemaToObjectTransformer
{
    public function __construct(
        private string $jsonSchemaPath,
        private string $baseNamespace,
        private string $baseDir,
    ) {}

    private function parseSourceFile(string $jsonSchemaFile): array
    {
        ['extension' => $ext, 'filename' => $fileName, 'dirname' => $fileDir] = pathinfo($jsonSchemaFile);
//        if ('yaml' === $ext) {
//            return (new YamlParser($sourceFilePath))->parse();
//        }

        if ('json' === $ext) {
            $data = json_decode(file_get_contents($jsonSchemaFile), true);
            return compact('fileName', 'fileDir', 'data');
        }

        throw new \RuntimeException("Unknown $ext extension of source file");
    }

    private function scanSource(): array
    {
        $jsonSchemaPathList = [];

        if (is_file($this->jsonSchemaPath)) {
            return [$this->jsonSchemaPath];
        }

        $dirIterator = new \RecursiveDirectoryIterator($this->jsonSchemaPath);
        $iterator = new \RecursiveIteratorIterator($dirIterator);

        foreach ($iterator as $file) {
            assert($file instanceof \SplFileInfo);
            if ($file->isFile()) {
                $jsonSchemaPathList[] = (string) $file;
            }
        }

        return $jsonSchemaPathList;
    }

    private function parseSource(): \Generator
    {
        $sourceList = $this->scanSource();

        foreach ($sourceList as $sourceFilePath) {
            yield $this->parseSourceFile($sourceFilePath);
        }
    }

    public function transform(): void
    {
        foreach ($this->parseSource() as $sourceSchema) {
            $this->generateSchema($sourceSchema);
        }
    }

    private function generateSchema(array $sourceSchema): void
    {
        $data = $sourceSchema['data'];
        $fileDir = str_replace($this->jsonSchemaPath, $this->baseDir, $sourceSchema['fileDir']);
        $fileClass = $sourceSchema['fileName'];
        $namespace = str_replace($this->jsonSchemaPath, $this->baseNamespace, $sourceSchema['fileDir']);
        $namespace = str_replace('/', '\\', $namespace);
        $schema = var_export($data, true);

        $class = <<<EOL
        <?php
        
        declare(strict_types=1);
        
        namespace $namespace;
        
        class $fileClass
        {
            public const SCHEMA = {$schema};
        }
        
        EOL;

        $filesystem = new Filesystem();

        if (false === $filesystem->isDir($fileDir)) {
            $filesystem->mkdir($fileDir, 0755, true);
        }

        if ($filesystem->fileExists("$fileDir/$fileClass.php")) {
            $filesystem->rm("$fileDir/$fileClass.php");
        }

        $filesystem->filePutContents("$fileDir/$fileClass.php", $class);
    }
}
