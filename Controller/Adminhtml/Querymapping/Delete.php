<?php
 /**
 * web-vision GmbH
 *
 * NOTICE OF LICENSE
 *
 * <!--LICENSETEXT-->
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.web-vision.de for more information.
 *
 * @category    WebVision
 *
 * @copyright   Copyright (c) 2001-2021 web-vision GmbH (https://www.web-vision.de)
 * @license     <!--LICENSEURL-->
 * @author      Parth Trivedi <parth@web-vision.de>
 */
declare(strict_types=1);
namespace WebVision\Unity\Controller\Adminhtml\Querymapping;

class Delete extends \WebVision\Unity\Controller\Adminhtml\Querymapping
{
    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('query_mapping_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\WebVision\Unity\Model\QueryMapping::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Query Mapping.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['query_mapping_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Query Mapping to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
