<?php

namespace Hj\Helper;

use JiraRestApi\Issue\IssueService;
use JiraRestApi\JiraException;
use Symfony\Component\Yaml\Yaml;

/**
 * Generate JQL from all select field
 *
 * Class JqlFileFieldWithOptionsGenerator
 * @package Hj\Helper
 */
class JqlFileFieldWithOptionsGenerator
{
    public function generate($issueKey, $jqlBasePath)
    {
        try {
            $service = new IssueService();
            $ret = $service->getEditMeta($issueKey);

            foreach ($ret as $result) {
                if ($result['schema']['type'] == ('option')) {
                   $this->generateYaml($result, $jqlBasePath);
                }
                if ($result['schema']['type'] == ('option-with-child')) {
                    $this->generateYaml($result, $jqlBasePath);
                }
            }
        } catch (JiraException $e) {
            echo $e->getMessage();
        }

    }

    /**
     * Crée le répertoire, le fichier de root, le fichier de mapping et les Yamls par option
     *
     * @param array $field
     * @param $jqlBasePath
     */
    private function generateYaml(array $field, $jqlBasePath)
    {
        $jqlPath = $jqlBasePath . $field['key'];

        if (!is_dir($jqlPath)) {
            mkdir($jqlPath, 0777, true);
        }
        $fileNames = [];
        $files = [];
        $name = $field['name'];
        $customFieldNameForJql = 'cf[' . $field['schema']['customId'] . ']';
        foreach ($field['allowedValues'] as $key => $value) {
            $fileName = $value['id'] . '.yaml';
            $fileNames[$key] = $jqlPath . '/' . $fileName;
            $files[$fileName] = $value['value'];
            $this->dumpJqlOnYaml($customFieldNameForJql, $jqlPath . '/' . $fileName, $value['value']);
        }
        $this->dumpJqlRootFileOnYaml($fileNames, $jqlPath . '/root.yaml');
        $this->generateMappingFile($name, $field['key'], $files, $jqlPath . '/mapping.yaml');
    }

    /**
     * Généré un fichier de mapping permettant de retrouver la correspondance entre :
     *  - le nom du répertoire et le nom du champ select
     *  - le nom du fichier yaml et le nom de l'option correspondante
     *
     * @param string $name nom du champ select sur l'interface (ex: sevérité)
     * @param string $key clé JIRA du champ (ex: customfield_11222)
     * @param array $files tableau de correspondance entre les noms des fichiers et la valeur (ou option) du select
     * @param string $filename le nom du fichier de mapping
     */
    private function generateMappingFile($name, $key, $files, $filename) {
        $yaml = Yaml::dump([
            'folder' => [
                'name' => $key,
                'fieldName' => $name,
            ],
            'files' => $files
        ]);

        file_put_contents($filename, $yaml);

    }

    /**
     * Génére le fichier JQL correspondant à l'option
     *
     * @param string $name nom du champ select sur l'interface (ex: sevérité)
     * @param string $fileName nom du fichier JQL
     * @param string $value valeur (ou option) du select
     */
    private function dumpJqlOnYaml($name, $fileName, $value)
    {
        $condition1 = 'AND "' . $name . '" = ' . '"' .$value . '"';
        $project = [
            'name' => 'Support client',
            'operator' => '=',
            'issueSuffix' => 'MLVDESK',
        ];
        $conditions = [
            $condition1,
        ];
        $expressions = [
            'order by issueKey ASC'
        ];

        $yaml = Yaml::dump([
            'project' => $project,
            'conditions' => $conditions,
            'expressions' => $expressions,
        ]);

        file_put_contents($fileName, $yaml);
    }

    /**
     * Généré le fichier qui va contenir le nom de tous les fichiers JQLS générés par option
     *
     * @param array $fileNames ensemble des fichiers JQL générés par valeur (option) du select
     * @param string $rootFileName nom du fichier JQL root
     */
    private function dumpJqlRootFileOnYaml($fileNames, $rootFileName)
    {
        $yaml = Yaml::dump([
            'files' =>
                $fileNames,
        ]);

        file_put_contents($rootFileName, $yaml);
    }
}