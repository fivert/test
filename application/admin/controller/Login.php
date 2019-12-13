<?php
namespace app\admin\controller;

class Login
{
    public function index()
    {
        return view();
    }

    public function getVerify(){
		WSTVerify();
    }
    
    /**
	 * 判断用户登录帐号密码
	 */
	public function checkLogin(){
		$loginName = input("post.loginName");
		$loginPwd = input("post.loginPwd");
		$code = input("post.verifyCode");
		// if(!WSTVerifyCheck($code)){
		// 	return WSTReturn('验证码错误!');
        // }
        
        $decrypt_data = WSTRSA($loginPwd);
		if($decrypt_data['status']==1){
			$loginPwd = $decrypt_data['data'];
		}else{
            return WSTReturn('登录失败');
        }
        
        $staff = db('staffs')->where(['loginName'=>$loginName,'staffStatus'=>1,'dataFlag'=>1])->find();
        if(empty($staff)) return WSTReturn('用户账号错误!');
		if($staff['loginPwd'] == $loginPwd){
            $staff['lastTime'] = date('Y-m-d H:i:s');
            $staff['lastIP'] = request()->ip();
            db('staffs')->update($staff);
            
	 		//记录登录日志
		 	// LogStaffLogins::create([
		 	//      'staffId'=>$staff['staffId'],
		 	//      'loginTime'=> date('Y-m-d H:i:s'),
		 	//      'loginIp'=>request()->ip()
		 	// ]);
	 		//获取角色权限
	 		// $role = Roles::get(['dataFlag'=>1,'roleId'=>$staff['staffRoleId']]);
	 		// $staff['roleName'] = $role['roleName'];
	 		// if($staff['staffId']==1){
	 		// 	$staff['privileges'] = Db::name('privileges')->where(['dataFlag'=>1])->column('privilegeCode');
	 		// 	$staff['menuIds'] = Db::name('menus')->where('dataFlag',1)->column('menuId');
	 		// }else{
		 	// 	$staff['privileges'] = ($role['privileges']!='')?explode(',',$role['privileges']):[];
		 	// 	$staff['menuIds'] = [];
		 	// 	//获取管理员拥有的菜单
		 	// 	if(!empty($staff['privileges'])){
		 	// 	     $menus = Db::name('menus')->alias('m')->join('__PRIVILEGES__ p','m.menuId=p.menuId and p.dataFlag=1','inner')
		 	// 	                ->where([['p.privilegeCode','in',$staff['privileges']]])->field('m.menuId')->select();
		 	// 	     $menuIds = [];
		 	// 	     if(!empty($menus)){
		 	// 	     	foreach ($menus as $key => $v){
		 	// 	     		$menuIds[] = $v['menuId'];
		 	// 	     	}
		 	// 	     	$staff['menuIds'] = $menuIds;
		 	// 	     }
		 	// 	}
	 		// }
	 		session("WST_STAFF",$staff);
			return WSTReturn("",1,$staff);
		}
		return WSTReturn('用户密码错误!');
	}
	
}
