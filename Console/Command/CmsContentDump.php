<?php declare(strict_types=1);

namespace Yireo\DumpCmsContent\Console\Command;

use Magento\Framework\Console\Cli;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Yireo\DumpCmsContent\Util\DumpCmsContent;

class CmsContentDump extends Command
{
    private const OPTION_INCLUDE_INACTIVE = 'include-inactive';

    public function __construct(
        private readonly DumpCmsContent $dumpCmsContent,
        ?string $name = null
    ) {
        parent::__construct($name);
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('cms:dump');
        $this->setDescription('Dump all active CMS pages and blocks in var/cms-output');
        $this->addOption(
            self::OPTION_INCLUDE_INACTIVE,
            null,
            InputOption::VALUE_NONE,
            'Also dump disabled CMS pages and blocks'
        );
    }

    /**
     * @throws FileSystemException
     * @throws LocalizedException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Dumping CMS content to var/cms-output');
        $count = $this->dumpCmsContent->execute((bool)$input->getOption(self::OPTION_INCLUDE_INACTIVE));
        $output->writeln('Files written: ' . $count);

        return Cli::RETURN_SUCCESS;
    }
}
