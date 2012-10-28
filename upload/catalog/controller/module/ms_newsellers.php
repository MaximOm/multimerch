<?php  
class ControllerModuleMsNewsellers extends ControllerSellerCatalog {
	protected function index($setting) {
		static $module = 0;
		
		if (isset($setting['limit']) && (int)$setting['limit'] > 0)
			$this->data['limit'] = (int)$setting['limit'];
		else
			$this->data['limit'] = 3;

		if (!isset($setting['width']) || (int)$setting['width'] <= 0)
			$setting['width'] = $this->config->get('config_image_category_width');
		
		if (!isset($setting['height']) || (int)$setting['height'] <= 0)
			$setting['height'] = $this->config->get('config_image_category_height');
			
		$this->data['sellers_href'] = $this->url->link('seller/catalog-seller');
				
		$data = array(
			'order_by'               => 'date_added',
			'order_way'              => 'DESC',
			'page'              => 1,
			'limit'              => $this->data['limit']
		);

		$results = $this->MsLoader->MsSeller->getSellers($data, TRUE);

		$this->data['sellers'] = array();
		foreach ($results as $result) {
			$this->data['sellers'][] = array(
				'nickname'        => $result['nickname'],
				'href'        => $this->url->link('seller/catalog-seller/profile','seller_id=' . $result['seller_id']),
				'image' => !empty($result['avatar_path']) && file_exists(DIR_IMAGE . $result['avatar_path']) ? $this->MsLoader->MsFile->resizeImage($result['avatar_path'], $setting['width'], $setting['height']) : $this->MsLoader->MsFile->resizeImage('ms_no_image.jpg', $setting['width'], $setting['height']) 
			);
		}
		
		$this->data['module'] = $module++; 
		list($this->template, $this->children) = $this->MsLoader->MsHelper->loadTemplate('module-newsellers', array());
		$this->response->setOutput($this->render());
	}
}
?>