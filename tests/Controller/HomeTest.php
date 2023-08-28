<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Yaml\Yaml;
use App\Controller\HomeController;

class HomeTest extends WebTestCase
{
    private string $host;
    private array $translations;
    
    public function setUp(): void
    {
        $this->host = getenv('HOST');
        $es = $this->parseMenuSections(Yaml::parseFile($this->getTranslationPath('messages.es.yml')));
        $en = $this->parseMenuSections(Yaml::parseFile($this->getTranslationPath('messages.en.yml')));
        $this->translations=['es' => $es, 'en' => $en];
    }

    private function getTranslationPath(string $file): string
    {
        return  __DIR__."/../../translations/{$file}";
    }

    private function parseMenuSections($data): array
    {
        $items = array_merge(HomeController::SECTIONS,['Login']);
        foreach ($items as $k => $item){
            $values[$k+1] = $data[$item];
        }
        return $values;
    }

    
    public function testEnglishResponse(): void
    {
        $client = static::createClient();
        $client->request('GET', '/', [], [], ['HTTP_HOST' => "en.{$this->host}"]);
    
        $this->assertResponseIsSuccessful();
        foreach($this->translations['en'] as $k => $text){
            $this->assertSelectorTextSame("nav#navbar ul li:nth-child({$k})",$text);
        }
    }

    public function testSpanishResponse(): void
    {
        $client = static::createClient();
        $client->request('GET', '/', [], [], ['HTTP_HOST' => "{$this->host}"]);
    
        $this->assertResponseIsSuccessful();
        foreach($this->translations['es'] as $k => $text){
            $this->assertSelectorTextSame("nav#navbar ul li:nth-child({$k})",$text);
        }
    }
}
