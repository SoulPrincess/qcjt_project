<?php
namespace api\controllers;
use api\models\login\LoginForm;
use vendor\tools\EncryptTools;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * Created by PhpStorm.
 * User: LHP
 * Date: 2020/4/2
 * Time: 11:51
 */
class LoginController extends PublicController{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @Notes:前台账号登录
     * @throws \yii\db\Exception
     * @User:lhp
     * @Time: 2020/04/02
     */
    public function actionLogin()
    {
        $login_form = new LoginForm();
        if ($this->is_post()) {
            $json = $this->get_json();
            $check_data['username'] = isset($json['username']) ? EncryptTools::DecryptRsa($json['username']) : '';
            $check_data['passwd'] = isset($json['passwd']) ? md5(EncryptTools::DecryptRsa($json['passwd'])) : '';
            $verify_token = isset($json['verify_token']) ? $json['verify_token'] : '';
            if ($check_data['username'] && $check_data['passwd']) {
                $verify = VerifyTools::identityVerify($verify_token);
                if ($verify == 0) {//滑动验证
                    //登录成功
                    if ($login_form->load($check_data, '') && $login_form->login()) {
                        $school = new School();
                        //创建session及相应业务操作
                        $user = Yii::$app->user->identity;
                        $session = Yii::$app->session;
                        $authority_token = session_id();
                        //获取用户角色及相应的权限信息
                        $login = User::findOne($user->id);
                        $login->last_login = time();
                        $login->authority_token = $authority_token;
                        $login->save();
                        if (in_array($user->role, [2, 3, 4])) {
                            //数据权限
                            $access = $school->getAccountAccess([
                                'm1.id' => $user->controller ? explode(',', $user->controller) : [1],
                                'm2.id' => $user->action ? explode(',', $user->action) : [],
                                'm3.id' => $user->operation ? explode(',', $user->operation) : []
                            ]);
                            $session->set('access', $access);
                            //院系专业班级权限账号
                            $session->set('dept', [
                                'department' => $user->department ? explode(',', $user->department) : [],
                                'major' => $user->major ? explode(',', $user->major) : [],
                                'class' => $user->class ? explode(',', $user->class) : [],
                            ]);
                            $session->set('no_authority', 1);
                        }
                        $this->_response([
                            'authority_token' => $authority_token]);
                    } else {//登录失败
                        $account = new Account();
                        $user = $account->accountDetail(['a.account' => $check_data['username']]);
                        if ($user) {
                            if (intval($user['status']) == 2) {
                                $this->_response([], 50002);//该账号已被禁用
                            } elseif (intval($user['status']) == 3) {
                                $this->_response([], 50003);//该账号已过期
                            } else {
                                //减少用户剩余登录次数及相应告警信息
                                $times = 0;
                                if ($user['times'] - 1 <= 0) {
                                    $data = [
                                        'times' => 0,
                                        'status' => 2,
                                    ];
                                } else {
                                    $times = $user['times'] - 1;
                                    $data = [
                                        'times' => $user['times'] - 1,
                                    ];
                                }
                                $account->updateAccount($data, ['id' => $user['id']]);
                                $this->_response([$user], 50001, '密码错误,剩余登录次数' . $times . '次');//密码错误
                            }
                        }
                        $this->_response([], 50001);//账号错误或不存在，应对等保检测
                    }
                }
                $this->_response([], $verify);//请先滑动验证
            }
            $this->_response([], 40001);//缺少登录必要参数
        }
        $this->_response([], 0, '准备登录 ');
    }
    /**
     * @Notes:用户注册
     * @throws \yii\db\Exception
     * @User:lhp
     * @Time: 2020/04/02
     */
    public function actionRegister()
    {
        if ($this->is_post()) {
            $enter = new Enterprise();
            $json = $this->get_json();
            $sms_code = $this->verifyEmpty($json, 'sms_code');
            $list['mobile'] = $this->verifyEmpty($json, 'mobile');
            if ($sms_code == '000000' && time() <= strtotime('2020-03-27 16:30:00')) {
                //跳过验证
            } else {
                $check = MsgTools::checkMsg($list['mobile'], $sms_code);
                if ($check == 50011) {
                    $this->_response([], 41011);
                } elseif ($check == 0) {
                    $this->_response([], 40014);
                }
            }
            $data['name'] = $this->verifyEmpty($json, 'name');
            $data['logo'] = $this->verifyEmpty($json, 'logo', 'company/public/default_logo.png');
            $data['social_code'] = $this->verifyEmpty($json, 'social_code');
            $data['address'] = $this->verifyEmpty($json, 'address');
            $data['company_province'] = $this->verifyEmpty($json, 'company_province');
            $data['company_city'] = $this->verifyEmpty($json, 'company_city');
            $data['lat'] = $this->verifyEmpty($json, 'lat');
            $data['lng'] = $this->verifyEmpty($json, 'lng');
            $data['email'] = $this->verifyEmpty($json, 'email');
            $data['fixed_tel'] = $this->verifyEmpty($json, 'fixed_tel');
            $data['recruit_email'] = $this->verifyEmpty($json, 'recruit_email');
            $data['zip_code'] = $this->verifyEmpty($json, 'zip_code');
            $data['company_nature'] = $this->verifyEmpty($json, 'company_nature');
            $data['company_industry'] = $this->verifyEmpty($json, 'company_industry');
            $data['company_scale'] = $this->verifyEmpty($json, 'company_scale', 1);
            $data['register_funds'] = $this->verifyEmpty($json, 'register_funds', 1);
            $data['label'] = $this->verifyEmpty($json, 'label');
            $data['company_license'] = $this->verifyEmpty($json, 'company_license');
            $data['license_time'] = $json['license_time'] ? strtotime($json['license_time']) : 0;
            $data['company_organize'] = $this->verifyEmpty($json, 'company_organize');
            $data['company_describe'] = $this->verifyEmpty($json, 'company_describe');
            $data['identify_face'] = $this->verifyEmpty($json, 'identify_face');
            $data['identify_side'] = $this->verifyEmpty($json, 'identify_side');
            $data['work_card'] = $this->verifyEmpty($json, 'work_card');
            $data['from_school'] = EncryptTools::UnLockData($this->verifyEmpty($json, 'school_token', 0));
            $data['status'] = 1;
            $data['data_rate'] = 70;
            $data['create_time'] = time();
            $data['update_time'] = time();
            $list['name'] = $this->verifyEmpty($json, 'contact');
            $list['passwd'] = md5($this->verifyEmpty($json, 'passwd'));
            $list['create_time'] = time();
            if (empty($data['company_license'])) {
                $this->_response([], -1, '请填写营业执照');
            }
            if (empty($data['company_organize'])) {
                $this->_response([], 1, '请填写组织机构证');
            }
            $check_name = $enter->enterpriseDetail(['e.name' => $data['name']]);
            if ($check_name) {
                if ($check_name['source'] == 2) {//删除猎聘公司
                    $enter->deleteEnterprise(['name' => $data['name'], 'source' => 2]);
                } else {
                    $this->_response([], 51001, '该单位名称已被注册,
                如需添加到此公司请联系单位管理员(' . $check_name['contact'] . ':' . $check_name['mobile'] . ')');
                }
            }
            $check_code = $enter->enterpriseDetail(['e.social_code' => $data['social_code']]);
            if ($check_code) {
                $this->_response([], -1, '该统一社会信用代码已被注册！');
            }
            $check_mobile = $enter->enterpriseContact(['c.mobile' => $list['mobile']]);
            if ($check_mobile) {
                $this->_response([], 51002);
            }
            $row = $enter->register($data, $list);
            if ($row) {
                $this->_response([], 0, '注册成功，请耐心等候审核结果');
            }
            $this->_response([$data], -1);
        }
    }
}
