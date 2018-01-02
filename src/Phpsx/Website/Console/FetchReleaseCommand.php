<?php

namespace Phpsx\Website\Console;

use DateTime;
use Phpsx\Website\Table;
use PSX\Framework\Config\Config;
use PSX\Http\Client;
use PSX\Http\GetRequest;
use PSX\Json\Parser;
use PSX\Record\RecordInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FetchReleaseCommand extends Command
{
    protected $releaseTable;
    protected $http;
    protected $config;

    public function __construct(Table\Release $releaseTable, Client $http, Config $config)
    {
        parent::__construct();

        $this->releaseTable = $releaseTable;
        $this->http         = $http;
        $this->config       = $config;
    }

    protected function configure()
    {
        $this
            ->setName('website:fetch_release')
            ->setDescription('Fetches the latest release from GitHub');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url      = sprintf('%s/repos/%s/%s/releases', $this->config['git_api'], $this->config['git_owner'], $this->config['git_repo']);
        $request  = new GetRequest($url, [
            'Accept'     => 'application/vnd.github.v3+json',
            'User-Agent' => 'PSX-Website-Updater (https://github.com/k42b3)',
        ]);
        $response = $this->http->request($request);

        if ($response->getStatusCode() == 200) {
            $count    = 0;
            $releases = Parser::decode((string) $response->getBody());

            foreach ($releases as $release) {
                if ($release->draft === false) {
                    $row = $this->releaseTable->getOneById($release->id);

                    if (!$row instanceof RecordInterface) {
                        $asset = $this->getAsset($release);

                        $this->releaseTable->create([
                            'id'          => $release->id,
                            'tagName'     => $release->tag_name,
                            'htmlUrl'     => $release->html_url,
                            'publishedAt' => new DateTime($release->published_at),
                            'authorName'  => $release->author->login,
                            'authorUri'   => $release->author->html_url,
                            'body'        => $release->body,
                            'assetName'   => isset($asset->name) ? $asset->name : '',
                            'assetUrl'    => isset($asset->browser_download_url) ? $asset->browser_download_url : '',
                            'assetSize'   => isset($asset->size) ? $asset->size : '',
                            'assetMime'   => isset($asset->content_type) ? $asset->content_type : '',
                        ]);

                        $count++;
                    }
                }
            }

            $output->writeln(sprintf('Inserted %s release/s', $count));
        } else {
            $output->writeln(sprintf('Received an status code %s', $response->getStatusCode()));
        }
    }

    protected function getAsset(\stdClass $release)
    {
        if (isset($release->assets) && is_array($release->assets)) {
            $asset = current($release->assets);

            if (isset($asset->state) && $asset->state == 'uploaded') {
                return $asset;
            }
        }

        return null;
    }
}
