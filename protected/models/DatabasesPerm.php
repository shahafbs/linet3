<?php
/***********************************************************************************
 * The contents of this file are subject to the Mozilla Public License Version 2.0
 * ("License"); You may not use this file except in compliance with the Mozilla Public License Version 2.0
 * The Original Code is:  Linet 3.0 Open Source
 * The Initial Developer of the Original Code is Adam Ben Hur.
 * All portions are Copyright (C) Adam Ben Hur.
 * All Rights Reserved.
 ************************************************************************************/
/**
 * This is the model class for table "databasesPerm".
 *
 * The followings are the available columns in table 'databasesPerm':
 * @property integer $id
 * @property integer $user_id
 * @property integer $database_id
 * @property integer $level_id
 */
class DatabasesPerm extends mainRecord{
    const table='databasesPerm';
    public function primaryKey(){
	    return 'id';
	}
    
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return (self::table);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, database_id, level_id', 'required'),
			array('user_id, database_id, level_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, database_id, level_id', 'safe', 'on'=>'search'),
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
                        'Company' => array(self::BELONGS_TO, 'Company', 'database_id'),
                        'User' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

        
         public function save($runValidation = true, $attributes = NULL) {
            $a=parent::save($runValidation,$attributes);
            if($a){
                
                Yii::app()->db->setActive(false);
                Yii::app()->db->connectionString = $this->Company->string;
                Yii::app()->db->tablePrefix=$this->Company->prefix;
                Yii::app()->db->setActive(true);
                
                $user=User::model()->findByPk($this->user_id);
                $user->save();
            }
            return $a;
        }
        
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'database_id' => 'Database',
			'level_id' => 'Level',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('database_id',$this->database_id);
		$criteria->compare('level_id',$this->level_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DatabasesPerm the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
