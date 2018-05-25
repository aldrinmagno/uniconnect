<?php

namespace Src\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class ProfileController extends BaseController
{
    public function myProfile(Request $request, Response $response, $args)
    {
        $segment = $this->session->getSegment('Aura\Session\SessionFactory');
        $user = $segment->get('userData');

        $userInfo = $this->users->findBy(['*'], 'fldUserId = :id', ['id' => $user['id']]);
        $apply = $this->useruni->findBy(['*'], 'tblUsers_fldUserId = :id', ['id' => $userInfo['fldUserId']]);
        $uni = $this->uni->findBy(['*'], 'fldUniId = :id', ['id' => $apply['tblUni_fldUniId']]);
        $country = $this->country->findBy(['*'], 'fldCountryId = :id', ['id' => $uni['tblCountry_fldCountryId']]);
        $degree = $this->degree->findAllBy(['*'], 'tblUni_fldUniId = :id', ['id' => $apply['tblUni_fldUniId']]);
      
        if( ! $user) {
            return $response->withStatus(302)->withHeader('Location', $this->router->pathFor('myprofile'));
        }
        
        $data = [
            'title' => "Dashboard",
            'uni' => $uni,
            'user' => $userInfo,
            'country' => $country,
            'degree' => $apply['fldDegreeId'],
            'degrees' => $degree
        ];

        $this->view->render($response, 'pages/profile/myprofile.html', $data);
        return $response;
    }

    public function updateChecklist(Request $request, Response $response, $args)
    {
        $input = $request->getParsedBody();
        
        $segment = $this->session->getSegment('Aura\Session\SessionFactory');
        $user = $segment->get('userData');

        $req = $this->checklist->findBy(['*'], 'fldCheckListUserId = :user AND fldCheckListRequirementId = :requirement', ['user' => $user['id'], 'requirement' => $input['id']]);

        if($req) {
            $select = [
                'fldCheckListStatus' => $input['check']
            ];
    
            $bind = [
                'user' => $user['id'],
                'requirement' => $input['id'],
                'fldCheckListStatus' => $input['check']
            ];
    
            if($this->checklist->update($select, 'fldCheckListUserId = :user AND fldCheckListRequirementId = :requirement', $bind)) {
                $res = [
                    'status' => 'success',
                    'description' => 'Your checklist was updated'
                ];
            } else {
                $res = [
                    'status' => 'error',
                    'description' => 'Whoops, something went wrong. Please try again.',
                ];
            }
        } else {
            $this->checklist->insert([
                'fldCheckListUserId' => $user['id'],
                'fldCheckListRequirementId' => $input['id'],
                'fldCheckListStatus' => $input['check']
            ]);

            $res = [
                'status' => 'success',
                'description' => 'Your checklist was updated'
            ];
        }
        

        // validation error occored during editing
        return json_encode($res);
        exit;

    }

    public function updateProfile(Request $request, Response $response, $args)
    {
        $input = $request->getParsedBody();

        // add validation rules
        $this->v->add([
            'txtFName:First name' => array('required'),
            'txtLName:Last name' => array('required'),
            'txtPhone:Phone' => array('required'),
        ]);

        if ($this->v->validate($input)) {     
            $segment = $this->session->getSegment('Aura\Session\SessionFactory');
            $user = $segment->get('userData');
    
            $user = $this->users->findBy(['*'], 'fldUserId = :id', ['id' => $user['id']]);

            $select = [
                'fldUserFName',
                'fldUserLName',
                'fldUserDOB',
                'fldUserStreet',
                'fldUserSuburb',
                'fldUserState',
                'fldUserPostcode',
                'fldUserEmail',
                'fldUserMobile'
            ];

            $bind = [
                'fldUserFName' => ($input['txtFName'] ? $input['txtFName'] : $user['fldUserFName']),
                'fldUserLName' => ($input['txtLName'] ? $input['txtLName'] : $user['fldUserLName']),
                'fldUserDOB' => ($input['txtDOB'] ? $input['txtDOB'] : $user['fldUserDOB']),
                'fldUserStreet' => ($input['txtStreet'] ? $input['txtStreet'] : $user['fldUserStreet']),
                'fldUserSuburb' => ($input['txtSuburb'] ? $input['txtSuburb'] : $user['fldUserSuburb']),
                'fldUserState' => ($input['txtState'] ? $input['txtState'] : $user['fldUserState']),
                'fldUserPostcode' => ($input['txtPostcode'] ? $input['txtPostcode'] : $user['fldUserPostcode']),
                'fldUserEmail' => ($input['txtEmail'] ? $input['txtEmail'] : $user['fldUserEmail']),
                'fldUserMobile' => ($input['txtPhone'] ? $input['txtPhone'] : $user['fldUserMobile']),
                'id' => $user['fldUserId']
            ];

            if($input['degree']) {
                $this->useruni->update(['fldDegreeId'], 'tblUsers_fldUserId = :id', ['fldDegreeId' => $input['degree'], 'id' => $user['fldUserId']]);
            }
            
            if($this->users->update($select, 'fldUserId = :id', $bind)) {
                $res = [
                    'status' => 'success',
                    'description' => 'Profile was updated!',
                ];
            } else {
                $res = [
                    'status' => 'failed',
                    'description' => 'Whoops, something went wrong. Please try again.',
                ];
            }
        } else {
            $res = [
                'status' => 'failed',
                'description' => 'Validation Error',
                'specific' => $this->errorMessage($this->v->getMessages())
            ];
        }

        // validation error occored during editing
        return json_encode($res);
        exit;

    }

    public function checkList(Request $request, Response $response, $args)
    {
        $segment = $this->session->getSegment('Aura\Session\SessionFactory');
        $user = $segment->get('userData');

        $userInfo = $this->users->findBy(['*'], 'fldUserId = :id', ['id' => $user['id']]);
        $apply = $this->useruni->findBy(['*'], 'tblUsers_fldUserId = :id', ['id' => $userInfo['fldUserId']]);
        $uni = $this->uni->findBy(['*'], 'fldUniId = :id', ['id' => $apply['tblUni_fldUniId']]);

        $docs = ['Step 1 Uni',
                'Step 2 Uni',
                'Step 2 Doc',
                'Step 3 Uni',
                'Step 4 Uni',
                'Step 4 Doc',
                'Step 5 Uni',
                'Step 6 Uni',
                'Step 6 Doc'];

        $uniRequirements = $this->requirement->findAllBy(['*'], 'tblUni_fldUniId = :id AND fldRequirementType IN (:docs)', ['id' => $uni['fldUniId'], 'docs' => $docs]);
        $visaRequirements = $this->requirement->findAllBy(['*'], 'tblUni_fldUniId = :id AND fldRequirementType = "Visa"', ['id' => $uni['fldUniId']]);
   
        $uniChecked = $this->checklist->findAllBy(['*'], 'fldCheckListUserId = :id', ['id' => $user['id']]);

        $uniReqId = [];
        foreach($uniRequirements as $row) {
            array_push($uniReqId, $row['fldRequirementId']);
        }

        $visaReqId = [];
        foreach($visaRequirements as $row) {
            array_push($visaReqId, $row['fldRequirementId']);
        }

        $uniCount = $this->checklist->countBy('fldCheckListRequirementId IN (:id) AND fldCheckListStatus = 1', ['id' => $uniReqId]);
        $visaCount = $this->checklist->countBy('fldCheckListRequirementId IN (:id) AND fldCheckListStatus = 1', ['id' => $visaReqId]);
       
        if( ! $user) {
            return $response->withStatus(302)->withHeader('Location', $this->router->pathFor('myprofile'));
        }
        
        $data = [
            'title' => "Get started with your academic dreams",
            'uni' => $uni,
            'user' => $userInfo,
            'degree' => $apply['fldDegreeId'],
            'uniReq' => ['req' => $uniRequirements],
            'visaReq' => ['req' => $visaRequirements],
            'checked' => $uniChecked,
            'uniPer' => (($uniCount['cnt'] / count($uniReqId)) * 100) / 2,
            'visaPer' => (($visaCount['cnt'] / count($visaReqId)) * 100) / 2
        ];

        $this->view->render($response, 'pages/profile/checklist.html', $data);
        return $response;
    }

    public function connect(Request $request, Response $response, $args)
    {
        $segment = $this->session->getSegment('Aura\Session\SessionFactory');
        $user = $segment->get('userData');

        $userInfo = $this->users->findBy(['*'], 'fldUserId = :id', ['id' => $user['id']]);
        $apply = $this->useruni->findBy(['*'], 'tblUsers_fldUserId = :id', ['id' => $userInfo['fldUserId']]);
        $uni = $this->uni->findBy(['*'], 'fldUniId = :id', ['id' => $apply['tblUni_fldUniId']]);
   
        if( ! $user) {
            return $response->withStatus(302)->withHeader('Location', $this->router->pathFor('myprofile'));
        }

        $data = [
            'title' => "Connect with other students",      
            'testimonies' =>  $this->testimony->homePage([
                'fldUniLogo AS image', 
                'CONCAT(fldUserFName, " ", fldUserLName) AS title', 
                'fldTestimony AS description',
                'fldUniName',
                'fldUniSlug',
                'fldCountrySlug',
                'fldUserMobile',
                'fldUserEmail'
            ], 'fldTestimonyDeleted = :del AND fldTestimoneyUniId = :uni', 
            ['del' => 0, 'uni' => $uni['fldUniId']]),
            'degree' => $apply['fldDegreeId'],
        ];

        $this->view->render($response, 'pages/profile/connect.html', $data);
        return $response;
    }

    public function activities(Request $request, Response $response, $args)
    {
        $segment = $this->session->getSegment('Aura\Session\SessionFactory');
        $user = $segment->get('userData');

        $userInfo = $this->users->findBy(['*'], 'fldUserId = :id', ['id' => $user['id']]);
        $apply = $this->useruni->findBy(['*'], 'tblUsers_fldUserId = :id', ['id' => $userInfo['fldUserId']]);
        $uni = $this->uni->findBy(['*'], 'fldUniId = :id', ['id' => $apply['tblUni_fldUniId']]);
   
        if( ! $user) {
            return $response->withStatus(302)->withHeader('Location', $this->router->pathFor('myprofile'));
        }

        $data = [
            'title' => "Activities",
            'degree' => $apply['fldDegreeId'],
        ];

        $this->view->render($response, 'pages/profile/activities.html', $data);
        return $response;
    }

    public function selectedUni(Request $request, Response $response, $args)
    {
        $segment = $this->session->getSegment('Aura\Session\SessionFactory');
        $user = $segment->get('userData');

        $userInfo = $this->users->findBy(['*'], 'fldUserId = :id', ['id' => $user['id']]);
        $apply = $this->useruni->findBy(['*'], 'tblUsers_fldUserId = :id', ['id' => $userInfo['fldUserId']]);
        $uni = $this->uni->findBy(['*'], 'fldUniId = :id', ['id' => $apply['tblUni_fldUniId']]);
        $country = $this->country->findBy(['*'], 'fldCountryId = :id', ['id' => $uni['tblCountry_fldCountryId']]);
   
        if( ! $user) {
            return $response->withStatus(302)->withHeader('Location', $this->router->pathFor('myprofile'));
        }
        
        $data = [
            'title' => "Your Selected University", 
            'user' => $userInfo,
            'uni' => $uni,
            'degree' => $apply['fldDegreeId'],
            'country' => $country,
            'degrees' => $degree,
            'testimonies' => $this->testimony->homePage([
                'fldUniLogo AS image', 
                'CONCAT(fldUserFName, " ", fldUserLName) AS title', 
                'fldTestimony AS description',
                'fldUniName',
                'fldUniSlug',
                'fldCountrySlug'
            ], 'fldTestimonyDeleted = :del AND fldTestimoneyUniId = :uni', 
            ['del' => 0, 'uni' => $uni['fldUniId']]),
            'visaReq' => $this->requirement->visaReq(),
            'uniReq' => $this->requirement->uni($uni['fldUniId'])
        ];

        $this->view->render($response, 'pages/profile/selected-uni.html', $data);
        return $response;
    }

    protected function errorMessage(array $messages)
    {
        $formatter = function ($value) {
            return (string) $value[0];
        };

        return array_map($formatter, $messages);
    }
}
