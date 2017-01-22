<?php
namespace App\Tests;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Base extends \PHPUnit_Framework_TestCase
{

    /*
     * @var mixed
     */
    protected $settings = [];

    /*
     * @var EntityManager
     */
    protected $em;

    public function setUp()
    {
        parent::setUp();
        $default = require __DIR__ . '/../settings.php';
        $local = require __DIR__ . '/../settings.local.php';

        $this->settings = array_replace_recursive($default, $local)['settings'];
        $this->setUpEntityManager();
        $this->generateSchema();
    }

    public function setUpEntityManager()
    {
        $settings = $this->settings['doctrine'];
        $paths = [$settings['entities']];
        $config = Setup::createAnnotationMetadataConfiguration($paths, $settings['dev']);
        $entityManager = EntityManager::create($settings['test_params'], $config);
        $this->em = $entityManager;
    }

    public function generateSchema()
    {
        $meta = $this->em->getMetadataFactory()->getAllMetadata();
        $tool = new SchemaTool($this->em);
        $tool->createSchema($meta);
    }

    public function test()
    {
        $this->assertTrue(true);
    }

    protected function request($client, $type, $url, $params = [])
    {
        try {
            $response = $client->request($type, $url, [
                'debug' => false,
                'form_params' => $params
            ]);
        } catch (RequestException $e) {
            $response = $e->getResponse();
        }
        $code = $response->getStatusCode();
        $contents = $response->getBody()->getContents();
        echo 'REQUEST [' . $type . '] [' . $url . '] PARAMS: ' . json_encode($params) . "\n";
        echo 'CODE: ' . $code . "\n";
        echo 'RESPONSE: ' . "\n";
        echo $contents;
        echo PHP_EOL;

        return ['body' => $contents, 'code' => $code];
    }


    protected function adminRequest($type, $url, $params = [])
    {

        $adminClient = $this->getClient();
        $this->request($adminClient, 'POST', '/api/members/login', [
            'username' => 'admin',
            'password' => 'admin'
        ]);
        $this->request($adminClient, $type, $url, $params);
    }

    protected function getClient()
    {
        $jar = new \GuzzleHttp\Cookie\CookieJar();
        $urlEndPoint = $this->settings['test_endpoint'];
        return new Client([
            'cookies' => $jar,
            'base_uri' => $urlEndPoint
        ]);
    }

    protected function registerRandomUserAndLogin($client)
    {
        $username = 'elchoice1_' . \rand();
        $password = 'elchoicedemo432';
        $response = $this->request($client, 'POST', '/api/members/register', [
            'username' => $username,
            'password' => $password,
        ]);

        $createdUser = json_decode($response['body'], true);

        $response = $this->request($client, 'POST', '/api/members/login', [
            'username' => $username,
            'password' => $password,
        ]);
        $decoded = json_decode($response['body'], true);
        $this->assertEquals(true, $decoded['login']);
        return $createdUser;
    }

    protected function deleteUser($user)
    {
        $this->adminRequest('DELETE', '/api/users/' . $user['id']);
    }


}
