<?php

namespace Phpsx\Website\Console;

use Phpsx\Website\Slugify;
use Phpsx\Website\Table;
use PSX\Framework\Config\Config;
use PSX\Http\Client\ClientInterface;
use PSX\Http\Client\GetRequest;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PackageBlogPostCommand extends Command
{
    protected $blogTable;
    protected $httpClient;
    protected $config;
    protected $slugify;

    public function __construct(Table\Blog $blogTable, ClientInterface $httpClient, Config $config)
    {
        parent::__construct();

        $this->blogTable  = $blogTable;
        $this->httpClient = $httpClient;
        $this->config     = $config;
        $this->slugify    = new Slugify();
    }

    protected function configure()
    {
        $this
            ->setName('website:package_blog_post')
            ->setDescription('Automatically fetches new package releases and creates a post');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $projects = json_decode(file_get_contents($this->config['projects_file']));

        foreach ($projects->primary as $name => $project) {
            $url = $project->git;
            $url = str_replace('.git', '/tags', $url);
            $url = str_replace('github.com', 'api.github.com/repos', $url);

            $request  = new GetRequest($url, [
                'Accept'     => 'application/vnd.github.v3+json',
                'User-Agent' => 'PSX-Website-Updater (https://github.com/k42b3)',
            ]);
            $response = $this->httpClient->request($request);

            if ($response->getStatusCode() == 200) {
                $tags = json_decode((string) $response->getBody());
                if (!empty($tags) && is_array($tags)) {
                    foreach ($tags as $tag) {
                        $this->insertTag($tag, $name, $project, $output);
                    }
                }
            }
        }

        return 0;
    }

    private function insertTag(\stdClass $tag, $projectName, \stdClass $project, OutputInterface $output)
    {
        $id   = 'urn:phpsx.org:tag:' . $tag->name;
        $post = $this->blogTable->get($id);

        if (empty($post)) {
            $request  = new GetRequest($tag->commit->url, [
                'Accept'     => 'application/vnd.github.v3+json',
                'User-Agent' => 'PSX-Website-Updater (https://github.com/chriskapp)',
            ]);
            $response = $this->httpClient->request($request);

            if ($response->getStatusCode() == 200) {
                $commit = json_decode((string) $response->getBody());

                $title   = sprintf('Released %s %s', $tag->name, $projectName);
                $summary = sprintf('Coverage of the %s release', $tag->name);
                $content = <<<HTML
<p>We have released version {$tag->name} of {$projectName}.<br>The complete
changelog is available at the <a href="{$project->git}">repository</a>.</p>
HTML;

                $this->blogTable->create([
                    'id'         => $id,
                    'title'      => $title,
                    'titleSlug'  => $this->slugify->slugify($title),
                    'authorName' => 'christoph.kappestein',
                    'authorUri'  => 'https://github.com/chriskapp',
                    'updated'    => new \DateTime($commit->commit->author->date),
                    'summary'    => $summary,
                    'category'   => ['release', 'psx'],
                    'content'    => $content,
                ]);

                $output->writeln(sprintf('Added release %s of %s', $tag->name, $projectName));
            } else {
                throw new \RuntimeException('Could not request commit details');
            }
        }
    }
}
