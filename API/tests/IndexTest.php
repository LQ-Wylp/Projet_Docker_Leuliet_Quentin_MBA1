<?php

use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
{
    public function testGetAllMessages()
    {
        // Simuler une requête GET
        $_SERVER['REQUEST_URI'] = 'http://localhost:3000/';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        ob_start();
        include '../src/index.php'; // Inclure le fichier index.php pour le tester
        $output = ob_get_clean();

        $this->assertJson($output); // Vérifier si la réponse est en format JSON
        $data = json_decode($output, true); // Convertir la réponse JSON en tableau associatif

        // Vérifier si la réponse contient les clés attendues
        $this->assertArrayHasKey('message', $data);
        $this->assertArrayHasKey('user', $data);
        $this->assertArrayHasKey('description', $data);
    }

    public function testAddMessage()
    {
        // Données à envoyer dans la requête POST
        $postData = array(
            'user' => 'John',
            'description' => 'New message'
        );

        // Simuler une requête POST
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = $postData;

        // Capturer la sortie de votre API
        ob_start();
        include '../src/index.php'; // Assurez-vous que le chemin est correct
        $output = ob_get_clean();

        // Vérifier la réponse de l'API
        $responseData = json_decode($output, true);

        // Assurez-vous que la réponse contient le message ajouté
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Message ajouté avec succès', $responseData['message']);
    }

    public function testDeleteMessage()
    {
        // Données à envoyer dans la requête DELETE
        $deleteData = array(
            'id' => 1 // ID du message à supprimer
        );

        // Simuler une requête DELETE
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $_DELETE = $deleteData; // Notez que $_DELETE n'est pas une variable superglobale standard, vous devrez peut-être la configurer dans votre environnement de test

        // Capturer la sortie de votre API
        ob_start();
        include '../src/index.php'; // Assurez-vous que le chemin est correct
        $output = ob_get_clean();

        // Vérifier la réponse de l'API
        $responseData = json_decode($output, true);

        // Assurez-vous que la réponse contient le message de succès attendu
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Message supprimé avec succès', $responseData['message']);
    }
}
