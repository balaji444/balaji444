<?php
	
	namespace app\controllers;
	
	use app\models\AccessControl;
	use app\models\CommonMethods;
	use app\models\Roles;
	use app\models\UserModule;
	use app\models\Users;
    use app\models\Admin;
	use Yii;
	use yii\web\Controller;
	
	class AdminController extends Controller
	{
		// ...existing code...
		
		public function actionAddPage()
		{
			$loggedInUid = 1;//CommonMethods::GetLoginUserId();
			if (!empty($loggedInUid)) {

				$session = Yii::$app->session;
				
				$arrAllControllerNames = CommonMethods::GetAllControllerNames();
				
				if (Yii::$app->request->post()) {

					$formPostValues = CommonMethods::sanitizeUrlQueryString(Yii::$app->request->post());
					
					$checkValueExsists = Admin::checkAlreadyExsists(TBL_HD_PAGE_MASTER, $formPostValues);
					
					if ($checkValueExsists > 0) {
						
						$session->setFlash('ErrorMessage', 'Action name already exists in controller');
						
						return $this->render('add_page',
							array('session' => $session, 'arrAllControllerNames' => $arrAllControllerNames));
					} else {
						$SaveValueInDB = Admin::SaveValueInDB(TBL_HD_PAGE_MASTER, $formPostValues);
					}
					
					return $this->redirect(array('admin/view-pages'));
					
				} else {
					// either the page is initially displayed or there is some validation error
					return $this->render('add_page',
						array('session' => $session, 'arrAllControllerNames' => $arrAllControllerNames));
				}
			}
		}
		
		
		public function actionViewPages()
		{
				
				$tableValuesResult = Admin::fetchTableValues(TBL_HD_PAGE_MASTER, 'page_added_by_user_id');
				return $this->render('view_pages', array("tableValuesResult" => $tableValuesResult,));

		}
		
		public function actionGetActionName()
		{
			$loggedInUid = 1;//CommonMethods::GetLoginUserId();
			if (!empty($loggedInUid)) {
                $request = Yii::$app->request;
                $controllerName = CommonMethods::sanitizeUrlQueryString($request->post('controller_name'));
				$arrControllerActions = CommonMethods::GetControllerActions(ucfirst($controllerName));
                return json_encode($arrControllerActions);
			}
		}
		
		public function actionEditPage()
		{

				$session = Yii::$app->session;
				

				$id = base64_decode(CommonMethods::sanitizeUrlQueryString(Yii::$app->request->get('page_id')));

				
				if (empty(Yii::$app->request->get('page_id')) && empty(Yii::$app->request->post())) {
					return $this->redirect(array('/admin/view-pages'));
				}
				
				$field_name = "page_id";
				$arrAllControllerNames = CommonMethods::GetAllControllerNames();
				if (Yii::$app->request->post()) {

					$formPostValues = CommonMethods::sanitizeUrlQueryString(Yii::$app->request->post());
					
					$checkValueExsists = Admin::checkAlreadyExsists(TBL_HD_PAGE_MASTER, $formPostValues);
					
					if ($checkValueExsists > 0) {
						
						$session->setFlash('ErrorMessage', 'Action name already exists in controller');
						if (empty($tblDatawithID)) {
							$ss = 0;
							foreach ($formPostValues as $postFldName => $pstFldValue) {
								$tblDatawithID[$postFldName] = $pstFldValue;
							}
						}
						//Get action of contorller
						$arrControllerActions = CommonMethods::GetControllerActions(ucfirst($tblDatawithID['controller_name']) . "Controller");
						$arrDataWIthIDHistory = Admin::fetchDataWIthIDHistory(TBL_HD_PAGE_MASTER_HISTORY,
							$tblDatawithID['page_id'], $field_name);
						
						return $this->render('edit_page', array(
							'session' => $session,
							'arrAllControllerNames' => $arrAllControllerNames,
							'tblDatawithID' => $tblDatawithID,
							'arrControllerActions' => $arrControllerActions,
							'arrDataWIthIDHistory' => $arrDataWIthIDHistory
						));
					} else {
						$SaveValueInDB = Admin::SaveHistoryofPagesandUpdate(TBL_HD_PAGE_MASTER_HISTORY,
							TBL_HD_PAGE_MASTER, $field_name, $formPostValues);
						return $this->redirect(array('/admin/view-pages'));
					}
					
				} else {
					
					
					//$arrControllerActions     = CommonMethods::GetAllControllerNames('SiteController');
					
					$tblDatawithID = Admin::fetchDataWIthID(TBL_HD_PAGE_MASTER, $id, $field_name);
					$arrDataWIthIDHistory = Admin::fetchDataWIthIDHistory(TBL_HD_PAGE_MASTER_HISTORY, $id,
						$field_name);
					
					//Get action of contorller
					$arrControllerActions = CommonMethods::GetControllerActions(ucfirst($tblDatawithID['controller_name']) . "Controller");
					
					// either the page is initially displayed or there is some validation error
					return $this->render('edit_page', array(
						'session' => $session,
						'arrAllControllerNames' => $arrAllControllerNames,
						'tblDatawithID' => $tblDatawithID,
						'arrControllerActions' => $arrControllerActions,
						'arrDataWIthIDHistory' => $arrDataWIthIDHistory
					));
				}
		}
		
		/*
	
		*/
		public function actionAddRole()
		{

				return $this->render("add_edit_role");

		}
		
		public function actionEditRole()
		{
			$loggedInUid = CommonMethods::GetLoginUserId();
				$editData = array();
				$dataExists = false;
				if (!empty(CommonMethods::sanitizeUrlQueryString(Yii::$app->request->get('req_roleid')))) {
					$decodedRoleId = base64_decode(CommonMethods::sanitizeUrlQueryString(Yii::$app->request->get('req_roleid')));
					if (!empty($decodedRoleId)) {
						$editDataRs = Roles::GetAllRoles($decodedRoleId);
						if (!empty($editDataRs)) {
							$dataExists = true;
							$editData['roleName'] = $editDataRs[0]['role_name'];
							$editData['roleDesc'] = $editDataRs[0]['role_description'];
							$editData['roleId'] = $editDataRs[0]['role_id'];
							$editData['role_Default_Page_Id'] = $editDataRs[0]['default_page_id'];
						}
					}
				}
				if ($dataExists == false) {
					return $this->redirect(CommonMethods::GetLoginUserDefaultCntrlAndAction());
				}
				return $this->render("add_edit_role", array('editData' => $editData));
		}

		public function actionShowPagesSuggest()
		{
			
			$loggedInUid = CommonMethods::GetLoginUserId();
				$pageName = "";
				if (!empty(CommonMethods::sanitizeUrlQueryString(Yii::$app->request->get('term')))) {
					$pageName = CommonMethods::sanitizeUrlQueryString(Yii::$app->request->get('term'));
				}
				
				if (!empty(CommonMethods::sanitizeUrlQueryString(Yii::$app->request->post('search')))) {
					$pageName = CommonMethods::sanitizeUrlQueryString(Yii::$app->request->post('search'));
				}
				if(!empty($pageName)) {	
					$pageDtls = UserModule::getPageDetails($pageName, TBL_HD_PAGE_MASTER);
                    return $pageDtls;
				}
		}
		

		public function actionShowHeadingSuggest()
		{
			
			$loggedInUid = CommonMethods::GetLoginUserId();
				$searchTerm = "";
				if (!empty(Yii::$app->request->get('term'))) {
					$searchTerm = CommonMethods::sanitizeUrlQueryString(Yii::$app->request->get('term'));
				}
				if (!empty(Yii::$app->request->post('search'))) {
					$searchTerm = CommonMethods::sanitizeUrlQueryString(Yii::$app->request->post('search'));
				}
				if (!empty($searchTerm)) {
					$headingDtls = UserModule::getHeadingDetails($searchTerm, TBL_HD_LEFTBAR_HEADING_MASTER);
                    return $headingDtls;
				}
		}

		
		public function actionSavemapingofpagestouser()
		{

            $loggedInUid = CommonMethods::GetLoginUserId();
                $formPostValues = Yii::$app->request->post();
                if (empty($formPostValues)) {
                    return "2";
                }
                $roleName = CommonMethods::sanitizeUrlQueryString($formPostValues['role_name']);
                $roleDesc = CommonMethods::sanitizeUrlQueryString($formPostValues['role_description']);

                if (empty($roleName)) {
                    return 'roleNameEmpty';
                }
                if (empty($roleDesc)) {
                    return 'roleDescEmpty';
                }
                $result = UserModule::SaveMappingRolesandPages($formPostValues);
                return $result;
			
		}
		
		public function actionViewRoles()
		{
				
				$tableValuesResult = Admin::fetchRoleValues(TBL_HD_ROLE_MASTER, 'role_added_by_user_id');
				return $this->render('view_roles', array("tableValuesResult" => $tableValuesResult,));
		}
		
		public function actionGetPagesAssignedToRole()
		{

            $postValue = Yii::$app->request->post();
            $roleId = CommonMethods::sanitizeUrlQueryString($postValue['role_id']);
            $pstDisplayType = CommonMethods::sanitizeUrlQueryString($postValue['pstDisplayType']);
            $arrPagesAssignedtoResult = Admin::fetchPagesAssignedToRoles($roleId, $pstDisplayType);
				
				$ajaxHtml = "<p>";
				foreach ($arrPagesAssignedtoResult as $res) {
					
					$ajaxHtml .= $res['page_name'] . "  <br />";
				}
				$ajaxHtml = substr($ajaxHtml, 0, -6);
				$ajaxHtml .= "</p>";

                return $ajaxHtml;

		}
		
		
		/* Start Users Related Actions */
		public function actionViewUsers()
		{
				$AllusrDtls = Users::GetAllUsers();
				return $this->render('view_users', array('showAllUsers' => $AllusrDtls));
		}
		
		public function actionAddUser()
		{
				$session = Yii::$app->session;

                $loggedInUid = CommonMethods::GetLoginUserId();
				$arrAllRoles = Roles::GetAllRoles();

				if (Yii::$app->request->post()) {
					$formPostValues = CommonMethods::sanitizeUrlQueryString(Yii::$app->request->post());

					$ValidationErrorMsgs = '';

					if (!empty(Yii::$app->request->post('user_email')) && !filter_var(Yii::$app->request->post('user_email'), FILTER_VALIDATE_EMAIL)) {
						$ValidationErrorMsgs .= "Please enter valid Email<br>";
					}
					if (empty(Yii::$app->request->post('user_role'))) {
						$ValidationErrorMsgs .= "Select Role<br>";
					}
                    if (empty(Yii::$app->request->post('user_phone_no'))) {
                        $ValidationErrorMsgs .= "Please enter phone number<br>";
                    }
					if (empty(Yii::$app->request->post('user_status'))) {
						$ValidationErrorMsgs .= "Select Status<br>";
					}
					if (!empty($ValidationErrorMsgs)) {
						$session->setFlash('ErrorMessage', $ValidationErrorMsgs);

                        $firstName = CommonMethods::sanitizeUrlQueryString(Yii::$app->request->post('first_name'));
                        $lastName = CommonMethods::sanitizeUrlQueryString(Yii::$app->request->post('last_name'));
                        $userEmail = CommonMethods::sanitizeUrlQueryString(Yii::$app->request->post('user_email'));
                        $userRole = CommonMethods::sanitizeUrlQueryString(Yii::$app->request->post('user_role'));
                        $userStatus = CommonMethods::sanitizeUrlQueryString(Yii::$app->request->post('user_status'));
                        $userNote = CommonMethods::sanitizeUrlQueryString(Yii::$app->request->post('user_note'));
				        $userPhoneNo = CommonMethods::sanitizeUrlQueryString(Yii::$app->request->post('user_phone_no'));

						$showDataArr = array();
						$showDataArr['first_name'] = $firstName;
						$showDataArr['last_name'] = $lastName;
						$showDataArr['user_email'] = $userEmail;
						$showDataArr['user_role'] = $userRole;
						$showDataArr['user_status'] = $userStatus;
						$showDataArr['user_note'] = $userNote;
						$showDataArr['user_phone_no'] = $userPhoneNo;
						
						return $this->render('add_user',
							array(
								'session' => $session,
								'arrAllRoles' => $arrAllRoles,
								'showDataDtlsArr' => $showDataArr
							));
					}
					
					$checkValueExsists = Users::checkUserAlreadyExists_or_not($formPostValues);
					
					if ($checkValueExsists > 0) {
						
						$session->setFlash('ErrorMessage', 'User Phone already exists');
                        $firstName = CommonMethods::sanitizeUrlQueryString(Yii::$app->request->post('first_name'));
                        $lastName = CommonMethods::sanitizeUrlQueryString(Yii::$app->request->post('last_name'));
                        $userEmail = CommonMethods::sanitizeUrlQueryString(Yii::$app->request->post('user_email'));
                        $userRole = CommonMethods::sanitizeUrlQueryString(Yii::$app->request->post('user_role'));
                        $userStatus = CommonMethods::sanitizeUrlQueryString(Yii::$app->request->post('user_status'));
                        $userNote = CommonMethods::sanitizeUrlQueryString(Yii::$app->request->post('user_note'));
						
						$userPhoneNo = CommonMethods::sanitizeUrlQueryString(Yii::$app->request->post('user_phone_no'));
                        $userTypeFromMaster = CommonMethods::sanitizeUrlQueryString(Yii::$app->request->post('team_member_type_master_id'));

						$showDataArr = array();
						$showDataArr['first_name'] = $firstName;
						$showDataArr['last_name'] = $lastName;
						$showDataArr['user_email'] = $userEmail;
						$showDataArr['user_role'] = $userRole;
						$showDataArr['user_status'] = $userStatus;
						$showDataArr['user_note'] = $userNote;
						$showDataArr['user_phone_no'] = $userPhoneNo;
                        $showDataArr['team_member_type_master_id'] = $userTypeFromMaster;
						
						return $this->render('add_user',
							array(
								'session' => $session,
								'arrAllRoles' => $arrAllRoles,
								'showDataDtlsArr' => $showDataArr
							));
					} else {
                        $saveValueInDB = Users::CreateUser($formPostValues, $loggedInUid);
                        return $this->redirect(array('/admin/view-users'));
					}
				} else {
					// either the page is initially displayed or there is some validation error
					return $this->render('add_user', array('session' => $session, 'arrAllRoles' => $arrAllRoles));
				}
		}
		
		public function actionEditUser()
		{
			$loggedInUid = CommonMethods::GetLoginUserId();

				$session = Yii::$app->session;
				if (empty(Yii::$app->request->post())) {
					if (!empty(Yii::$app->request->get('req_uid'))) {
						$Uid_val = base64_decode(CommonMethods::sanitizeUrlQueryString(Yii::$app->request->get('req_uid')));
					}
					if (empty(Yii::$app->request->get('req_uid'))) {
						return $this->redirect(array('/admin/view-users'));
					}
				}
				if (!empty(Yii::$app->request->post()) && !empty(Yii::$app->request->post('hdn_uid'))) {
					$Uid_val = base64_decode(CommonMethods::sanitizeUrlQueryString(Yii::$app->request->post('hdn_uid')));
				}
				if (empty($Uid_val)) {
					return $this->redirect(array('/admin/view-users'));
				}
				
				$arrAllRoles = Roles::GetAllRoles();
				$showDataArr = array();
				if (empty($_POST)) {
					$GetusrDtls = Users::GetAllUsers($Uid_val);
					if (!empty($GetusrDtls)) {
						foreach ($GetusrDtls as $uidDataArr) {
							$showDataArr['first_name'] = $uidDataArr['first_name'];
							$showDataArr['last_name'] = $uidDataArr['last_name'];
							$showDataArr['user_email'] = $uidDataArr['user_email'];
							$showDataArr['user_status'] = $uidDataArr['is_active'];
							$showDataArr['user_note'] = $uidDataArr['user_note'];
							$showDataArr['user_phone_number'] = $uidDataArr['user_phone_number'];
							$showDataArr['user_id'] = $uidDataArr['user_id'];
							$roleId = Roles::GetRoleIdOfUser($uidDataArr['user_id']);
							$showDataArr['user_role'] = $roleId;

						}
					}
				}
				if (Yii::$app->request->post()) {
					$formPostValues = CommonMethods::sanitizeUrlQueryString(Yii::$app->request->post());

                    $firstName = CommonMethods::sanitizeUrlQueryString(Yii::$app->request->post('first_name'));
                    $lastName = CommonMethods::sanitizeUrlQueryString(Yii::$app->request->post('last_name'));
                    $userEmail = CommonMethods::sanitizeUrlQueryString(Yii::$app->request->post('user_email'));
                    $userRole = CommonMethods::sanitizeUrlQueryString(Yii::$app->request->post('user_role'));
                    $userStatus = CommonMethods::sanitizeUrlQueryString(Yii::$app->request->post('user_status'));
                    $userNote = CommonMethods::sanitizeUrlQueryString(Yii::$app->request->post('user_note'));
			        $userPhoneNo = CommonMethods::sanitizeUrlQueryString(Yii::$app->request->post('user_phone_no'));

					if (empty(Yii::$app->request->post('hdn_uid'))) {
						return $this->redirect(array('/admin/view-users'));
					}
					$Uid_val = trim(base64_decode(CommonMethods::sanitizeUrlQueryString(Yii::$app->request->post('hdn_uid'))));
					
					if (empty($Uid_val)) {
						return $this->redirect(array('/admin/view-users'));
					}
					
					$ValidationErrorMsgs = '';

                    if (!empty(Yii::$app->request->post('user_email')) && !filter_var(Yii::$app->request->post('user_email'), FILTER_VALIDATE_EMAIL)) {
                        $ValidationErrorMsgs .= "Please enter valid Email<br>";
                    }
                    if (empty(Yii::$app->request->post('user_role'))) {
                        $ValidationErrorMsgs .= "Select Role<br>";
                    }
                    if (empty(Yii::$app->request->post('user_phone_no'))) {
                        $ValidationErrorMsgs .= "Please enter phone number<br>";
                    }
                    if (empty(Yii::$app->request->post('user_status'))) {
                        $ValidationErrorMsgs .= "Select Status<br>";
                    }

					
					if (!empty($ValidationErrorMsgs)) {
						$session->setFlash('ErrorMessage', $ValidationErrorMsgs);
						
						$showDataArr = array();
						$showDataArr['first_name'] = $firstName;
						$showDataArr['last_name'] = $lastName;
						$showDataArr['user_email'] = $userEmail;
						$showDataArr['user_role'] = $userRole;
						$showDataArr['user_status'] = $userStatus;
						$showDataArr['user_note'] = $userNote;
						$showDataArr['user_id'] = $Uid_val;
						$showDataArr['user_phone_no'] = $userPhoneNo;
						return $this->render('add_user',
							array(
								'session' => $session,
								'arrAllRoles' => $arrAllRoles,
								'showDataDtlsArr' => $showDataArr
							));
					}
					$checkValueExsists = Users::checkUserAlreadyExists_or_not($formPostValues, $Uid_val);
					if ($checkValueExsists > 0) {
						$showDataArr = array();
						$showDataArr['first_name'] = $firstName;
						$showDataArr['last_name'] = $lastName;
						$showDataArr['user_email'] = $userEmail;
						$showDataArr['user_role'] = $userRole;
						$showDataArr['user_status'] = $userStatus;
						$showDataArr['user_note'] = $userNote;
						$showDataArr['user_id'] = $Uid_val;
						$showDataArr['user_phone_no'] = $userPhoneNo;
						$session->setFlash('ErrorMessage', 'User Email Already Exists');
						
						return $this->render('add_user',
							array(
								'arrAllRoles' => $arrAllRoles,
								'showDataDtlsArr' => $showDataArr,
								'session' => $session
							));
					} else {
						$SaveValueInDB = Users::UpdateUser($formPostValues, $loggedInUid);
						return $this->redirect(array('/admin/view-users'));
					}
					
				} else {
					// either the page is initially displayed or there is some validation error
					return $this->render('add_user',
						array('arrAllRoles' => $arrAllRoles, 'showDataDtlsArr' => $showDataArr, 'session' => $session));
				}
		}
		/* End Users Related Actions */
		
		
		/* Start Users Related Actions */
		public function actionAddLeftbarHeading()
		{
			$session = Yii::$app->session;

				if (Yii::$app->request->post()) {
					// valid data received in $model
					
					// do something meaningful here about $model ...

					$formPostValues = Yii::$app->request->post();
					
					
					$checkValueExsists = UserModule::HeadingNameExsists(TBL_HD_LEFTBAR_HEADING_MASTER,
						$formPostValues);
					
					if ($checkValueExsists > 0) {
						
						$session->setFlash('ErrorMessage', 'Heading Name already exists.');
						
						return $this->render('add_leftbar_heading', array('session' => $session,));
					} else {
						$SaveValueInDB = UserModule::SaveLefbarHeading(TBL_HD_LEFTBAR_HEADING_MASTER, $formPostValues);
					}
					
					return $this->redirect(array('/admin/view-leftbar-headings'));
					
				} else {
					$session = Yii::$app->session;
					return $this->render('add_leftbar_heading', array("session" => $session));
				}
		}
		
		public function actionViewLeftbarHeadings()
		{

				
				$tableValuesResult = UserModule::fetchTableValues(TBL_HD_LEFTBAR_HEADING_MASTER,
					'heading_added_by_user_id');
				return $this->render('view_leftbar_headings', array("tableValuesResult" => $tableValuesResult,));

		}
		
		/* Start Users Related Actions */
		public function actionEditLeftbarHeading()
		{
			
			if (empty(Yii::$app->request->get('heading_id')) && empty(Yii::$app->request->post())) {
				return $this->redirect(array('/ap/view-leftbar-headings'));
			}
			
			$session = Yii::$app->session;

				
				$field_name = "heading_id";
				$id = base64_decode(CommonMethods::sanitizeUrlQueryString(Yii::$app->request->get('heading_id')));
				$tblDatawithID = UserModule::fetchDataWIthID(TBL_HD_LEFTBAR_HEADING_MASTER, $id, $field_name);
				
				
				if (Yii::$app->request->post()) {
					// valid data received in $model
					
					// do something meaningful here about $model ...

					$formPostValues = Yii::$app->request->post();
					$id = CommonMethods::sanitizeUrlQueryString(Yii::$app->request->post('heading_id'));
					
					$tblDatawithID = UserModule::fetchDataWIthID(TBL_HD_LEFTBAR_HEADING_MASTER, $id, $field_name);
					
					$checkValueExsists = UserModule::HeadingNameExsists(TBL_HD_LEFTBAR_HEADING_MASTER,
						$formPostValues);
					
					if ($checkValueExsists > 0) {
						
						$session->setFlash('ErrorMessage', 'Heading Name already exists.');
						
						return $this->render('edit_leftbar_heading',
							array('session' => $session, "tblDatawithID" => $tblDatawithID));
					} else {
						$SaveValueInDB = UserModule::EditLefbarHeading(TBL_HD_LEFTBAR_HEADING_MASTER_HISTORY,
							TBL_HD_LEFTBAR_HEADING_MASTER, $field_name, $formPostValues);
					}
					
					return $this->redirect(array('/admin/view-leftbar-headings'));
					
				} else {
					return $this->render('edit_leftbar_heading',
						array("session" => $session, "tblDatawithID" => $tblDatawithID));
				}
		}

        public function actionLogout()
        {

            $LoggedUserId = CommonMethods::GetLoginUserId();

            $LoggedUserRoleId = CommonMethods::GetLoginUserRoleId();
            $QueryResult = Users::SaveLogoutUserDetails($LoggedUserId, $LoggedUserRoleId);

            $session = Yii::$app->session;
            $session->open();
            $session->destroy();

            if (MANAGE_LOGIN_ACCOUNT_DETAILS == 'cookie') {

                if (isset($_COOKIE['LoggedUserTime'])) {
                    unset($_COOKIE['LoggedUserTime']);
                    setcookie('LoggedUserTime', '', time() - 3600, '/');
                }
                if (isset($_COOKIE['LoggedUserId'])) {
                    unset($_COOKIE['LoggedUserId']);
                    setcookie('LoggedUserId', '', time() - 3600, '/');
                }
                if (isset($_COOKIE['LoggedUserEmail'])) {
                    unset($_COOKIE['LoggedUserEmail']);
                    setcookie('LoggedUserEmail', '', time() - 3600, '/');
                }
                if (isset($_COOKIE['LoggedUserFirstName'])) {
                    unset($_COOKIE['LoggedUserFirstName']);
                    setcookie('LoggedUserFirstName', '', time() - 3600, '/');
                }
                if (isset($_COOKIE['LoggedUserLastName'])) {
                    unset($_COOKIE['LoggedUserLastName']);
                    setcookie('LoggedUserLastName', '', time() - 3600, '/');
                }
                if (isset($_COOKIE['LoggedUserPic'])) {
                    unset($_COOKIE['LoggedUserPic']);
                    setcookie('LoggedUserPic', '', time() - 3600, '/');
                }

                if (isset($_COOKIE['LoggedUserRoleId'])) {
                    unset($_COOKIE['LoggedUserRoleId']);
                    setcookie('LoggedUserRoleId', '', time() - 3600, '/');
                }
                if (isset($_COOKIE['LoggedUserRolePageIds'])) {
                    unset($_COOKIE['LoggedUserRolePageIds']);
                    setcookie('LoggedUserRolePageIds', '', time() - 3600, '/');
                }
                if (isset($_COOKIE['LoggedUserRoleDefaultPageId'])) {
                    unset($_COOKIE['LoggedUserRoleDefaultPageId']);
                    setcookie('LoggedUserRoleDefaultPageId', '', time() - 3600, '/');
                }
                if (isset($_COOKIE['LoggedUserRoleDefaultCntrlAndAction'])) {
                    unset($_COOKIE['LoggedUserRoleDefaultCntrlAndAction']);
                    setcookie('LoggedUserRoleDefaultCntrlAndAction', '', time() - 3600, '/');
                }
                if (isset($_COOKIE['LoggedUserPageControlesAndActions'])) {
                    unset($_COOKIE['LoggedUserPageControlesAndActions']);
                    setcookie('LoggedUserPageControlesAndActions', '', time() - 3600, '/');
                }

                if (isset($_COOKIE['LoggedUserTheme'])) {
                    unset($_COOKIE['LoggedUserTheme']);
                    setcookie('LoggedUserTheme', '', time() - 3600, '/');
                }

                Yii::$app->cache->flush();
            }

            //$QryLastActive = Users::updateUserLastActiveTime($LoggedUserId);

            return $this->redirect(array('/site/login'));
        }

        public function actionUploadFiles()
        {

            return $this->render('uploadFiles');
        }

        public function actionUploadFilesAjax()
        {

            $loggedInUid = CommonMethods::GetLoginUserId();
                $this->layout = false;
                set_time_limit(0);
                ini_set('memory_limit', '-1');
                ini_set("error_display", 1);
                ini_set('max_input_time', '-1');

                $search = array(
                    "'<script[^>]*?>.*?</script>'si",
                    "'<[\/\!]*?[^<>]*?>'si",
                    "'([\r\n])[\s]+'",
                    "'&(quot|#34);'i",
                    "'&(amp|#38);'i",
                    "'&(lt|#60);'i",
                    "'&(gt|#62);'i",
                    "'&(nbsp|#160);'i",
                    "'&(iexcl|#161);'i",
                    "'&(cent|#162);'i",
                    "'&(pound|#163);'i",
                    "'&(copy|#169);'i",
                    "'&#(\d+);'e"
                );

                $replace = array(
                    "",
                    "",
                    "\\1",
                    "\"",
                    "&",
                    "<",
                    ">",
                    " ",
                    chr(161),
                    chr(162),
                    chr(163),
                    chr(169),
                    "chr(\\1)"
                );

            $formPostValues = Yii::$app->request->post();

            $uploadType = CommonMethods::sanitizeUrlQueryString($formPostValues['upload_type']);
            if($uploadType != 'youtube_url') {
                $DestinationDir = UPLOAD_FILES_SERVER_PATH;

                if ($_FILES["upload_files"]["size"] > 10000000) {//10 MB
                    return "Signature having more size.";
                }
                if (isset($_FILES["upload_files"])) {
                    if (!empty($_FILES["upload_files"]["error"])) {
                        $Subject = "All Variables while File Upload Error Occured";
                        $Message = "Defined Variables <br><br> " . DOMAIN_NAME . "/admin/upload-files";
                        $Message .= preg_replace($search, $replace, json_encode(get_defined_vars()));
                        $Message .= "<br><br> System Variables <br><br>";
                        $Message .= preg_replace($search, $replace, json_encode(ini_get_all()));
                        $From = NO_REPLY_EMAIL;
                        $Headers = 'MIME-Version: 1.0' . "\r\n";
                        $Headers .= "From: Sathsang <$From>\r\n";
                        $Headers .= "Reply-To:  " . NO_REPLY_EMAIL . " \r\n";
                        $Headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                        $To = "balajimada8@gmail.com";
                        CommonMethods::SendMail($Subject, $Message, $To);
                        return "FileUpload Error";//$_FILES["upload_files"]["error"];
                    }
                }

                    //Checking Size0 File
                    $UploadedFileSize = filesize($_FILES["upload_files"]["tmp_name"]);

                    if ($UploadedFileSize == 0) {
                        return "SizeZero";
                    }
                    $loggedIn_user_id = $loggedInUid;
                    $ext = pathinfo($_FILES["upload_files"]["name"], PATHINFO_EXTENSION);

                    //System Generated File Name
                    $filename = $_FILES["upload_files"]["name"];
                    $file_orignal_name = CommonMethods::fnGetFileNameWithoutSpecialChars($_FILES['upload_files']['name']);
                    if (empty($file_orignal_name)) {
                        return "Invalid File Name";
                    }



                    //move_uploaded_file

                        if (move_uploaded_file($_FILES["upload_files"]["tmp_name"], $DestinationDir . '/' . $filename)) {

                            $isFileAlreadyExists = Admin::fnCheckFileAlreadyExists($filename);
                            if(!empty($isFileAlreadyExists)) {
                                return "File Exists";
                            }
                            /*$Upload = CommonMethods::UploadFileToS3($filename, $DestinationDir, S3_BUCKET_FILE_UPLOADS);
                            sleep(5);
                            if ($Upload) {
                                $isFileUploadedInS3 = CommonMethods::FileExistInS3Bucket($filename, S3_BUCKET_FILE_UPLOADS);
                                if ($isFileUploadedInS3) {

                                    if (file_exists($DestinationDir . $filename)) {

                                        unlink($DestinationDir . '/' . $filename);
                                    }
                                }
                            }*/
                        }
            } else {
                            $filename = CommonMethods::sanitizeUrlQueryString($formPostValues['youtube_url']);
                        }



                            $title = CommonMethods::sanitizeUrlQueryString($formPostValues['title']);

                            $description = CommonMethods::sanitizeUrlQueryString($formPostValues['description']);
                            Admin::fnInsertFileUploadData($title, $description, $uploadType,$filename);
                            return "Success";

        }


        public function actionViewUploadedFiles()
        {

            $tableValuesResult = Admin::fetchTableValues(TBL_HD_CONTENT, 'uploaded_by_user_id');
            return $this->render('view_uploaded_files', array("tableValuesResult" => $tableValuesResult,));

        }

	}