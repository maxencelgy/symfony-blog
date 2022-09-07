<?php

namespace App\Test\Controller;

use App\Entity\Articles;
use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticlesControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ArticlesRepository $repository;
    private string $path = '/articles/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(Articles::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Article index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'article[title]' => 'Testing',
            'article[contenu]' => 'Testing',
            'article[slug]' => 'Testing',
            'article[updated_at]' => 'Testing',
            'article[created_at]' => 'Testing',
        ]);

        self::assertResponseRedirects('/articles/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Articles();
        $fixture->setTitle('My Title');
        $fixture->setContenu('My Title');
        $fixture->setSlug('My Title');
        $fixture->setUpdated_at('My Title');
        $fixture->setCreated_at('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Article');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Articles();
        $fixture->setTitle('My Title');
        $fixture->setContenu('My Title');
        $fixture->setSlug('My Title');
        $fixture->setUpdated_at('My Title');
        $fixture->setCreated_at('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'article[title]' => 'Something New',
            'article[contenu]' => 'Something New',
            'article[slug]' => 'Something New',
            'article[updated_at]' => 'Something New',
            'article[created_at]' => 'Something New',
        ]);

        self::assertResponseRedirects('/articles/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getContenu());
        self::assertSame('Something New', $fixture[0]->getSlug());
        self::assertSame('Something New', $fixture[0]->getUpdated_at());
        self::assertSame('Something New', $fixture[0]->getCreated_at());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Articles();
        $fixture->setTitle('My Title');
        $fixture->setContenu('My Title');
        $fixture->setSlug('My Title');
        $fixture->setUpdated_at('My Title');
        $fixture->setCreated_at('My Title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/articles/');
    }
}
