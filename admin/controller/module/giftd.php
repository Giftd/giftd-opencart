<?php
class ControllerModuleGiftd extends Controller{
    private $error = array();
    
    public function index(){   
        $this->language->load('module/giftd');

        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('setting/setting');
                
        if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()){
            if((!$this->request->post['giftd_api_key'] && $this->config->get('giftd_api_key')) || (!$this->request->post['giftd_user_id'] && $this->config->get('giftd_user_id'))){
                $this->uninstall();
            }
            $this->model_setting_setting->editSetting('giftd', $this->request->post);        
            
            $this->cache->delete('product');
            
            $this->session->data['success'] = $this->language->get('text_success');
                        
            $this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
        }
                
        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_api_key']      = $this->language->get('text_api_key');
        $this->data['text_user_id']      = $this->language->get('text_user_id');
        $this->data['text_partner_code'] = $this->language->get('text_partner_code');
        $this->data['text_prefix']       = $this->language->get('text_prefix');        
        
        $this->data['button_save']   = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');
        $this->data['button_remove'] = $this->language->get('button_remove');
        
         if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }
        
        if (isset($this->error['image'])) {
            $this->data['error_image'] = $this->error['image'];
        } else {
            $this->data['error_image'] = array();
        }
        
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_module'),
            'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );
        
        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('module/giftd', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );
        
        $this->data['action'] = $this->url->link('module/giftd', 'token=' . $this->session->data['token'], 'SSL');
        
        $this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
        
        if(isset($this->request->post['giftd_api_key'])){
            $this->data['giftd_api_key'] = $this->request->post['giftd_api_key'];
        }elseif($this->config->get('giftd_api_key')){ 
            $this->data['giftd_api_key'] = $this->config->get('giftd_api_key');
        }else{
            $this->data['giftd_api_key'] = '';
        }
        
        if(isset($this->request->post['giftd_user_id'])){
            $this->data['giftd_user_id'] = $this->request->post['giftd_user_id'];
        }elseif($this->config->get('giftd_user_id')){ 
            $this->data['giftd_user_id'] = $this->config->get('giftd_user_id');
        }else{
            $this->data['giftd_user_id'] = '';
        }
        
        if($this->data['giftd_user_id'] && $this->data['giftd_api_key']){
            $partner_data = $this->get_data($this->data['giftd_user_id'], $this->data['giftd_api_key']);
        }
        
        if(isset($this->request->post['giftd_partner_code'])){
            $this->data['giftd_partner_code'] = $this->request->post['giftd_partner_code'];
        }elseif($this->config->get('giftd_partner_code')){ 
            $this->data['giftd_partner_code'] = $this->config->get('giftd_partner_code');
        }elseif(isset($partner_data['data']['code']) && ($partner_data['data']['code'])){ 
            $this->data['giftd_partner_code'] = $partner_data['data']['code'];
        }else{
            $this->data['giftd_partner_code'] = '';
        }                
        
        if(isset($this->request->post['giftd_prefix'])){
            $this->data['giftd_prefix'] = $this->request->post['giftd_prefix'];
        }elseif($this->config->get('giftd_prefix')){ 
            $this->data['giftd_prefix'] = $this->config->get('giftd_prefix');
        }elseif(isset($partner_data['data']['token_prefix']) && ($partner_data['data']['token_prefix'])){ 
            $this->data['giftd_prefix'] = $partner_data['data']['token_prefix'];
        }else{
            $this->data['giftd_prefix'] = '';
        }
        
        $js_code = '';

        if((!$this->config->get('giftd_code_updated')) || ($this->config->get('giftd_code_updated') && ((time() - $this->config->get('giftd_code_updated')) > 24 * 3600))){
            if($this->data['giftd_user_id'] && $this->data['giftd_api_key']){
                $js_code = $this->get_js($this->data['giftd_user_id'], $this->data['giftd_api_key']);
            }
            $this->data['giftd_code_updated'] = time();
        }else{
            $this->data['giftd_code_updated'] = $this->config->get('giftd_code_updated');
        } 
        echo 5;
        if($js_code){
            $this->data['giftd_js_code'] = $js_code;
        }elseif($this->config->get('giftd_js_code')){
            $this->data['giftd_js_code'] = $this->config->get('giftd_js_code');
        }else{
            $this->data['giftd_js_code'] = '';
        }               

        $this->template = 'module/giftd.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );
                
        $this->response->setOutput($this->render());
    }
    
    protected function validate() {
        if (!$this->user->hasPermission('modify', 'module/giftd')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
                
        if (!$this->error) {
            return true;
        } else {
            return false;
        }    
    }
    
    protected function get_data($user_id, $api_key){
        $data = array(
            'email' => $this->config->get('config_email'),
            'phone' => $this->config->get('config_telephone'),
            'name' => $this->config->get('config_owner'),
            'url' => HTTP_SERVER,
            'title' => $this->config->get('config_name'), 
            'opencart_version' => VERSION 
        );
        
        require_once(DIR_SYSTEM . 'GiftdApiClient.php');
        
        $client = new Giftd_Client($user_id, $api_key);
        $result = $client->query("openCart/install", $data);
        
        return $result;
    }
    
    protected function get_js($user_id, $api_key){
        require_once(DIR_SYSTEM . 'GiftdApiClient.php');
        
        $client = new Giftd_Client($user_id, $api_key);
        $result = $client->query('partner/getJs');

        $code = isset($result['data']['js']) ? $result['data']['js'] : false;
        
        return $code;
    }
    
    public function uninstall(){
        require_once(DIR_SYSTEM . 'GiftdApiClient.php');
        
        if($this->config->get('giftd_user_id') && $this->config->get('giftd_api_key')){
            $client = new Giftd_Client($this->config->get('giftd_user_id'), $this->config->get('giftd_api_key'));
            $result = $client->query("openCart/uninstall");
        }
        
        $this->load->model('module/giftd');
        
        $this->model_module_giftd->uninstall();

    }
    
    public function install(){
        $this->load->model('module/giftd');
        
        $this->model_module_giftd->install();
    }
} 
?>