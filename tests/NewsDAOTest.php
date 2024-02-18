<?php

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use App\Dao\NewsDAO;
use App\Utils\DB;
use App\Model\News;

class NewsDAOTest extends TestCase
{
    private MockObject $dbMock;
    private NewsDAO $newsDAO;

    protected function setUp(): void
    {
        //Mock the DB class
        $this->dbMock = $this->createMock(DB::class);

        // Instantiate the NewsDAO with the mock DB
        $this->newsDAO = new NewsDAO($this->dbMock);
    }

    public function testListAllReturnsArrayOfNews()
    {
        //Arange
        $fakeData = [
            ['id' => 1, 'title' => 'Test News', 'body' => 'This is a test', 'created_at' => '2021-01-01']
        ];
        $this->dbMock->method('select')->willReturn($fakeData);

        // Act
        $newsList = $this->newsDAO->listAll();

        $this->assertIsArray($newsList);
        $this->assertInstanceOf(News::class, $newsList[0]);
        $this->assertEquals('Test News', $newsList[0]->getTitle());
    }

    public function testAddReturnsIdOnSuccess()
    {
        // Arrange
        $this->dbMock->method('executeUpdate')->willReturn(1);

        // Act
        $result = $this->newsDAO->add(['title' => 'Test', 'body' => 'Test body']);

        // Assert
        $this->assertEquals(1, $result);
    }

    public function testDeleteReturnsFalseOnError()
    {
        // Arrange
        $this->dbMock->expects($this->once())
            ->method('beginTransaction');
        $this->dbMock->method('executeUpdate')->will($this->throwException(new PDOException()));
        $this->dbMock->expects($this->once())->method('rollBack');

        // Act
        $result = $this->newsDAO->delete(1);

        // Assert
        $this->assertFalse($result);
    }
}
