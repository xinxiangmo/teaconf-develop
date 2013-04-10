<?php

/**
 * This is the model class for table "topic_watch".
 *
 * The followings are the available columns in table 'topic_watch':
 * @property string $id
 * @property string $topic_id
 * @property string $user_id
 * @property string $created_at
 */
class TopicWatch extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{topic_watch}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('topic_id, user_id, created_at', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, topic_id, user_id, created_at', 'safe', 'on'=>'search'),
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
			'topic_id' => 'Topic',
			'user_id' => 'User',
			'created_at' => 'Created At',
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
		$criteria->compare('topic_id',$this->topic_id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('created_at',$this->created_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TopicWatch the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function behaviors()
    {
        return array(
            'timestamp' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'created_at',
                'updateAttribute' => null,
                'timestampExpression' => time(),
            ),
        );
    }

    /**
     * create topic watch
     *
     * @param integer $userId
     * @param integer $topicId
     * @return boolean
     */
    public static function watch($userId, $topicId)
    {
        $model = new self();
        $model->user_id = $userId;
        $model->topic_id = $topicId;
        return $model->save();
    }

    /**
     * delete topic watch
     *
     * @param integer $userId
     * @param integer $topicId
     * @return boolean
     */
    public static function unwatch($userId, $topicId)
    {
        return self::model()->deleteAllByAttributes(array(
            'user_id' => $userId,
            'topic_id' => $topicId,
        ));
    }

    /**
     * has watched 
     *
     * @param integer $userId
     * @param integer $topicId
     * @return boolean
     */
    public static function hasWatched($userId, $topicId)
    {
        return self::model()->findByAttributes(array(
            'user_id' => $userId,
            'topic_id' => $topicId,
        ));
    }
}
