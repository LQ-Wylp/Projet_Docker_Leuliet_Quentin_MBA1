<?php

use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
{
    public function testGetAllJoueurStats()
    {
        $_SERVER['REQUEST_URI'] = 'http://localhost:3000/joueur_stats';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        ob_start();
        include '../src/index.php';
        $output = ob_get_clean();

        $this->assertJson($output);
        $data = json_decode($output, true);

        $this->assertArrayHasKey('nom_joueur', $data[0]);
        $this->assertArrayHasKey('attaque', $data[0]);
        $this->assertArrayHasKey('vie', $data[0]);
        $this->assertArrayHasKey('niveau', $data[0]);
    }

    public function testAddJoueurStats()
    {
        $postData = json_encode(array(
            'nom_joueur' => 'John',
            'attaque' => 100,
            'vie' => 200,
            'niveau' => 10
        ));

        $_SERVER['REQUEST_URI'] = '/joueur_stats';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = array();
        parse_str($postData, $_POST);

        ob_start();
        include '../src/index.php';
        $output = ob_get_clean();

        $responseData = json_decode($output, true);

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Statistiques du joueur ajoutées avec succès', $responseData['message']);
    }

    public function testDeleteJoueurStats()
    {
        $deleteData = array(
            'id' => 1
        );

        $_SERVER['REQUEST_URI'] = '/joueur_stats';
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $_DELETE = $deleteData; 

        ob_start();
        include '../src/index.php';
        $output = ob_get_clean();

        $responseData = json_decode($output, true);

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Statistiques du joueur supprimées avec succès', $responseData['message']);
    }
}