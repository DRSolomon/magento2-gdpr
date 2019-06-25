<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Gdpr\Controller\Privacy;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Opengento\Gdpr\Api\ExportInterface;
use Opengento\Gdpr\Controller\AbstractPrivacy;
use Opengento\Gdpr\Model\Archive\MoveToArchive;
use Opengento\Gdpr\Model\Config;

/**
 * Action Export Export
 */
class Export extends AbstractPrivacy
{
    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    private $fileFactory;

    /**
     * @var \Opengento\Gdpr\Model\Archive\MoveToArchive
     */
    private $moveToArchive;

    /**
     * @var \Opengento\Gdpr\Api\ExportInterface
     */
    private $exportManagement;

    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Opengento\Gdpr\Model\Config $config
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Opengento\Gdpr\Model\Archive\MoveToArchive $moveToArchive
     * @param \Opengento\Gdpr\Api\ExportInterface $exportManagement
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        Context $context,
        Config $config,
        FileFactory $fileFactory,
        MoveToArchive $moveToArchive,
        ExportInterface $exportManagement,
        Session $customerSession
    ) {
        $this->fileFactory = $fileFactory;
        $this->moveToArchive = $moveToArchive;
        $this->exportManagement = $exportManagement;
        $this->customerSession = $customerSession;
        parent::__construct($context, $config);
    }

    /**
     * @inheritdoc
     */
    protected function isAllowed(): bool
    {
        return parent::isAllowed() && $this->config->isExportEnabled();
    }

    /**
     * @inheritdoc
     */
    protected function executeAction()
    {
        try {
            $customerId = (int) $this->customerSession->getCustomerId();
            $fileName = $this->exportManagement->exportToFile($customerId, 'customer', 'personal_data');
            $archiveFileName = 'customer_privacy_data_' . $customerId . '.zip';

            return $this->fileFactory->create(
                $archiveFileName,
                [
                    'type' => 'filename',
                    'value' => $this->moveToArchive->prepareArchive($fileName, $archiveFileName),
                    'rm' => true,
                ],
                DirectoryList::TMP
            );
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, new Phrase('Something went wrong, please try again later!'));
        }

        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $resultRedirect->setRefererOrBaseUrl();
    }
}
