<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class BookCategoryControllerTest extends WebTestCase
{
    private $client;
    private $bookId;
    private $categoryId;
    private $libraryId;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->libraryId = $this->createLibraryAndReturnId();
        $this->bookId = $this->createBookAndReturnId();
        $this->categoryId = $this->createCategoryAndReturnId();
    }

    public function testIndex(): void
    {
        $this->client->request('GET', '/book/category/');
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    /**
     * Test successful scenario
     */
    public function testCreateOK(): int
    {
        $data = [
            "book" => $this->bookId,
            "category" => $this->categoryId
        ];

        $this->client->request('POST', '/book/category/', [], [], [], json_encode($data));
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        return $responseData['id'];
    }

    /**
     * Test failure scenario
     */
    public function testCreateKO(): void
    {

        $data = [
            "book" => 0,
            "category" => 0,
        ];

        $this->client->request('POST', '/book/category/', [], [], [], json_encode($data));
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    /**
     * Test successful scenario
     * @depends testCreateOK
     */
    public function testShowOK($bookCategoryId): void
    {

        $this->client->request('GET', "/book/category/{$bookCategoryId}");
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    /**
     * Test failure scenario
     */
    public function testShowKO(): void
    {

        $this->client->request('GET', '/book/category/' . $this->getLastIdPlusOne());
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    /**
     * Test successful scenario
     * @depends testCreateOK
     */
    public function testUpdateOK($bookCategoryId): void
    {
        $this->client->request('PUT', "/book/category/{$bookCategoryId}", [], [], [], json_encode(['loan_date' => '2023-07-25']));
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    /**
     * Test failure scenario
     */
    public function testUpdateKO(): void
    {

        $this->client->request('PUT', '/book/category/' . $this->getLastIdPlusOne(), [], [], [], json_encode(['loan_date' => '2023-07-25']));
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    /**
     * Test successful scenario
     * @depends testCreateOK
     */
    public function testDeleteOK($bookCategoryId): void
    {

        $this->client->request('DELETE', "/book/category/{$bookCategoryId}");
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    /**
     * Test failure scenario
     */
    public function testDeleteKO(): void
    {

        $this->client->request('DELETE', '/book/category/' . $this->getLastIdPlusOne());
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    private function getLastIdPlusOne(): int
    {
        $bookRepository = $this->client->getContainer()->get('doctrine')->getRepository(\App\Entity\BookCategory::class);
        $lastId = $bookRepository->findMaxId();
        return $lastId + 1;
    }

    private function createBookAndReturnId(): int
    {
        $library = BookControllerTest::createBook($this->client, $this->libraryId);
        $responseData = json_decode($library->getContent(), true);
        return $responseData['id'];
    }

    private function createCategoryAndReturnId(): int
    {
        $library = CategoryControllerTest::createCategory($this->client);
        $responseData = json_decode($library->getContent(), true);
        return $responseData['id'];
    }

    private function createLibraryAndReturnId(): int
    {
        $library = LibraryControllerTest::createLibrary($this->client);
        $responseData = json_decode($library->getContent(), true);
        return $responseData['id'];
    }
}