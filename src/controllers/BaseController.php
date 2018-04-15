<?php

namespace Src\Controller;

use Slim\Container;

/**
 * @author Aldrin Magno <aldrin@strategylions.com.au>
 * @link https://www.strategylions.com.au/
 */
class BaseController
{
    // packages
    protected $view;
    protected $v;
    protected $vh;
    protected $csrf;
    protected $session;
    protected $mailer;
    protected $optimus;
    protected $curl;
    protected $link;
    protected $shortner;
  
    // models
    protected $users;
    protected $departments;
    protected $employees;
    protected $nextkin;
    protected $userdepartment;
    protected $franchisee;
    protected $franchises;
    protected $supplies;
    protected $supplied;
    protected $files;
    protected $notes;
    protected $inboxes;
    protected $allocate;
    protected $postalcodes;
    protected $options;
    protected $usergroups;
    protected $permissions;
    protected $grants;
    protected $filters;
    protected $leads;
    protected $states;
    protected $task;
    protected $configs;

    protected $burstSMSId;
    protected $burstSMSPassw;

    protected $users_list;

    public function __construct(Container $c)
    {
        // get settings
        $settings = $c->get('settings');

        // packages
        $this->view = $c->get('view');
        $this->v = $c->get('v');
        $this->vh = $c->get('vh');
        $this->session = $c->get('session');
        $this->csrf = $c->get('csrf');
        $this->mailer = $c->get('mailer');
        $this->optimus = $c->get('optimus');
        $this->curl = $c->get('curl');
        $this->link = $c->get('link');
        $this->shortner = $c->get('shortner');

        // models
        $this->users = $c->get('users');
        $this->departments = $c->get('departments');
        $this->employees = $c->get('employees');
        $this->nextkin = $c->get('nextkin');
        $this->userdepartment = $c->get('userdepartment');
        $this->franchisee = $c->get('franchisee');
        $this->franchises = $c->get('franchises');
        $this->supplies = $c->get('supplies');
        $this->supplied = $c->get('supplied');
        $this->files = $c->get('files');
        $this->notes = $c->get('notes');
        $this->inboxes = $c->get('inboxes');
        $this->allocate = $c->get('allocate');
        $this->postalcodes = $c->get('postalcodes');
        $this->options = $c->get('options');
        $this->usergroups = $c->get('usergroups');
        $this->permissions = $c->get('permissions');
        $this->grants = $c->get('grants');
        $this->filters = $c->get('filters');
        $this->leads = $c->get('leads');
        $this->states = $c->get('states');
        $this->task = $c->get('task');
        $this->configs = $c->get('configs');

        // Credentials
        $this->burstSMSId    = $settings['burstSMS']['user'];
        $this->burstSMSPassw = $settings['burstSMS']['password'];

        // Improve this
        $this->users_list = $this->users->findAllBy(['fldUserId', 'CONCAT(fldUserFName, " ", fldUserLName) AS name'], 'fldUserDeleted = :del AND tblUserGroups_fldUserGroupId = :group_id', ['del' => 0, 'group_id' => 3]);
     
    }

    /**
    * Summary: Check if user has proper permission
    *
    * Description:
    *
    * @param  	bool	$restriction	user permission
    * @access 	public
    * @author 	Aldrin Magno 
    * @version	0.1
    * @todo		
    */
    public function check($restriction) 
    {
        // check if user has proper permission
        if($restriction === 0) {
            // call session
            $segment = $this->session->getSegment('Aura\Session\SessionFactory');
            // store message in flash session
            $segment->setFlash("msg", "403 unauthorized page.");
            // redirect user to 403 page
            header('Location: https://api.wavessandbar.com.au/403-forbidden');
        }
    }

    /**
    * Summary: View all franchise admin users records
    *
    * Description: View all franchise users.
    *
    * @param  	date	$start	datetime token was created
    * @param  	time	$expires	time token will expire
    * @return 	bool
    * @access 	public
    * @author 	Aldrin Magno 
    * @version	0.1
    * @todo		
    */
    public function laps($start, $expires)
    {
        // check if expires exist
        if($expires) {
            // add start with expires
            $expiredAt = date('Y-m-d H:i:s',strtotime('+' . $expires . ' seconds',strtotime($start)));
            
            $date1 = new \Datetime($expiredAt);
            $date2 = new \Datetime(date("Y-m-d H:i:s"));

            // check if the expired date is greater than or equal to the current date
            if($date1 >= $date2) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
    * Summary: Refresh token
    *
    * Description:
    *
    * @param  	bool	$restriction	user permission
    * @return 	array   $refresh    refresh new token
    * @access 	public
    * @author 	Aldrin Magno 
    * @version	0.1
    * @todo		
    */
    public function refreshToken() 
    {
        // call session
        $segment = $this->session->getSegment('Aura\Session\SessionFactory');
        $token = $segment->get('token');

        // Call google oauth2
        $data = [
            'refresh_token' => $segment->get('refresh_token'),
            'client_id'     => '699226687962-4id8r0fqph9uaoo84ra53fsuttemkqke.apps.googleusercontent.com',
            'client_secret' => 'COlx3Nw5UJX8W6hOycLOjHsa',
            'grant_type' => 'refresh_token'
        ];
        $this->curl->post("https://www.googleapis.com/oauth2/v4/token", $data);

        // store refresh token
        $refresh = $this->curl->response;
        $segment->set('token',['token' => $refresh->access_token, 'type' => $refresh->token_type, 'expires' => $refresh->expires_in, 'created' => date('Y-m-d H:i:s')]);

        return json_encode($refresh);
    }
}
