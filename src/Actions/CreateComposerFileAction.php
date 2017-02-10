<?php

namespace llstarscreamll\Crud\Actions;

use llstarscreamll\Crud\Traits\FolderNamesResolver;

/**
 * PortoFoldersGeneration Class.
 *
 * @author Johan Alvarez <llstarscreamll@hotmail.com>
 */
class CreateComposerFileAction
{
    use FolderNamesResolver;

    /**
     * Container name to generate.
     *
     * @var string
     */
    public $container = '';

    /**
     * @param string $container Contaner name
     *
     * @return bool
     */
    public function run(string $container)
    {
        $this->container = studly_case($container);

        $composerFile = $this->containerFolder().'/composer.json';
        $template = $this->templatesDir().'.Porto/composer';

        $content = view($template, ['gen' => $this]);

        file_put_contents($composerFile, $content) === false
            ? session()->push('error', 'Error creating composer file')
            : session()->push('success', 'Composer file creation success');

        return true;
    }
}