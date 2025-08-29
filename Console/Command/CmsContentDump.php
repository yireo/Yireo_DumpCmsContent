<?php declare(strict_types=1);

namespace Yireo\DumpCmsContent\Console\Command;

use Magento\Framework\Console\Cli;
use Magento\Framework\Exception\FileSystemException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Yireo\DumpCmsContent\Util\DumpCmsContent;

class CmsContentDump extends Command
{
    /**
     * @var DumpCmsContent
     */
    private $dumpCmsContent;

    /**
     * CmsContentDump constructor.
     * @param DumpCmsContent $dumpCmsContent
     * @param string|null $name
     */
    public function __construct(
        DumpCmsContent $dumpCmsContent,
        string $name = null
    ) {
        parent::__construct($name);
        $this->dumpCmsContent = $dumpCmsContent;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('cms:dump');
        $this->setDescription('Dump all CMS pages and blocks in var/cms-output');
    }

    /**
     * CLI command description
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws FileSystemException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Dumping CMS content to var/cms-output');
        $this->dumpCmsContent->execute();
        return Cli::RETURN_SUCCESS;
    }
}
