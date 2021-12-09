<?php
class ModelExtensionModuleMessor extends Model
{

  public function LoadSettings()
  {
    return $this->config->get('module_messor_status');
  }

  public function createEvents()
  {
    $this->load->model('setting/event');
    $this->model_setting_event->addEvent('messor', "admin/view/common/column_left/before", "extension/module/messor/addLeftColumn", 1, 0);
    $this->model_setting_event->addEvent('messor', "catalog/controller/*/before", "extension/module/messor/alertMessor", 1, 0);
    $this->model_setting_event->addEvent('messor', "catalog/controller/error/not_found/before", "extension/module/messor/detect", 1, 0);
  }

  public function deleteEvents()
  {
    $this->load->model('setting/event');
    $this->model_setting_event->deleteEventByCode('messor');
  }

  public function getInstallId()
  {
    $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension_install` WHERE `filename` LIKE '%messor%'");
		return $query;
  }

  public function deleteExtensionPathOfInstall($extension_install_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "extension_path` WHERE `extension_install_id` = '" . (int)$extension_install_id . "'");
	}
}
