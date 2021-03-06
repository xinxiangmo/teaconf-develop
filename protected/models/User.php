<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $secure_code
 * @property string $signature
 * @property string $avatar_small
 * @property string $avatar_middle
 * @property string $avatar_large
 * @property string $weibo
 * @property string $qq
 * @property string $created_at
 * @property string $updated_at
 * @property string $last_posted_at
 * @property string $notifications
 */
class User extends ActiveRecord
{
    public $newPassword;
    public $confirmPassword;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
            // global
            array('name, password', 'required'),
			array('name', 'length', 'min' => 2, 'max' => 20),
            array('password', 'length', 'min' => 6),
            array('email', 'email'),
			array('created_at, updated_at, last_posted_at, notifications', 'length', 'max'=>11),
			array('email, password, secure_code, signature, avatar_small, avatar_middle, avatar_large, weibo, qq', 'length', 'max'=>255),

            // register
            array('name, email', 'unique', 'className' => 'User', 'on' => 'insert'),

            // changePassword
            array('newPassword, confirmPassword', 'required', 'on' => 'changePassword'),
            array('newPassword', 'compare', 'compareAttribute' => 'confirmPassword', 'on' => 'changePassword'),
            array('newPassword', 'length', 'min' => 6, 'on' => 'changePassword'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => '用户名',
			'email' => '邮箱',
			'password' => '密码',
            'newPassword' => '新密码',
            'confirmPassword' => '确认新密码',
			'secure_code' => '重要操作安全码',
			'signature' => '签名',
			'avatar_small' => '小头像',
			'avatar_middle' => '中头像',
			'avatar_large' => '大头像',
			'weibo' => 'weibo id',
			'qq' => 'qq id',
			'created_at' => '创建时间',
			'updated_at' => '修改时间',
			'last_posted_at' => '最后发帖时间',
            'notifications' => '未读提醒数',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('secure_code',$this->secure_code,true);
		$criteria->compare('signature',$this->signature,true);
		$criteria->compare('avatar_small',$this->avatar_small,true);
		$criteria->compare('avatar_middle',$this->avatar_middle,true);
		$criteria->compare('avatar_large',$this->avatar_large,true);
		$criteria->compare('weibo',$this->weibo,true);
		$criteria->compare('qq',$this->qq,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);
		$criteria->compare('last_posted_at',$this->last_posted_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function behaviors()
    {
        return array(
            'timestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'created_at',
                'updateAttribute' => 'updated_at',
                'timestampExpression' => time(),
            ),
        );
    }

    protected function beforeSave()
    {
        $return = parent::beforeSave();
        if($this->isNewRecord)
            $this->updated_at = $this->last_posted_at = $this->created_at;
        return $return;
    }

    public function getIteratorAttributes()
    {
        $attributes = parent::getIteratorAttributes();
        $attributes['avatar_large'] = AvatarUtil::absoluteUrl($attributes['avatar_large']);
        $attributes['avatar_middle'] = AvatarUtil::absoluteUrl($attributes['avatar_middle']);
        $attributes['avatar_small'] = AvatarUtil::absoluteUrl($attributes['avatar_small']);
        unset($attributes['password']);
        unset($attributes['created_at']);
        unset($attributes['updated_at']);
        unset($attributes['last_posted_at']);
        return $attributes;
    }

    public static function generateSecureCode()
    {
        return md5(StrUtil::random(10));
    }

    /**
     * Find user by name or email
     *
     * @static
     * @param string $id
     * @return User
     */
    public static function findById($id)
    {
        $attribute = 'name';
        if(strpos($id, '@') !== false)
        {
            $id = strtolower($id);
            $attribute = 'email';
        }

        return self::model()->find("{$attribute} = :id", array(':id' => $id));
    }

    /**
     * Update user last action
     *
     * @static
     * @param integer $id
     * @return boolean
     */
    public static function updateLastPostedAt($id)
    {
        return self::model()->updateByPk($id, array(
            'last_posted_at' => time(),
        ));
    }
}
