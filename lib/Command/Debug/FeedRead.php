<?php
declare(strict_types=1);

namespace OCA\NewsYawks\Command\Debug;

use OCA\NewsYawks\Controller\ApiPayloadTrait;
use OCA\NewsYawks\Db\ListType;
use OCA\NewsYawks\Service\Exceptions\ServiceConflictException;
use OCA\NewsYawks\Service\Exceptions\ServiceNotFoundException;
use OCA\NewsYawks\Service\FeedServiceV2;
use OCA\NewsYawks\Service\FolderServiceV2;
use OCA\NewsYawks\Service\ItemServiceV2;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ItemRead
 *
 * @package OCA\NewsYawks\Command
 */
class FeedRead extends Command
{
    /**
     * @var FeedServiceV2 service for the folders.
     */
    protected $feedService;

    /**
     * @var ItemServiceV2 service for the items.
     */
    protected $itemService;

    public function __construct(FeedServiceV2 $feedService, ItemServiceV2 $itemService)
    {
        parent::__construct();

        $this->feedService = $feedService;
        $this->itemService = $itemService;
    }

    /**
     * Configure command
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('news:feed:read')
            ->setDescription('Read feed')
            ->addArgument('user-id', InputArgument::REQUIRED, 'User to modify the feed for')
            ->addArgument('id', InputArgument::REQUIRED, 'Feed ID');
    }

    /**
     * Execute command
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = $input->getArgument('user-id');

        $id = $input->getArgument('id');
        if (!is_numeric($id)) {
            $output->writeln('Invalid id!');
            return 255;
        }

        try {
            $read = $this->feedService->read($user, intval($id));
            $output->writeln("Marked $read items as read", $output::VERBOSITY_VERBOSE);
        } catch (ServiceConflictException | ServiceNotFoundException $e) {
            $output->writeln('Failed: ' . $e->getMessage());
            return 0;
        }

        return 0;
    }
}
