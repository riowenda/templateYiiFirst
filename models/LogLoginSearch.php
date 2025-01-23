<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LogLogin;
use app\models\LogCrud;

/**
 * LogLoginSearch represents the model behind the search form of `app\models\LogLogin`.
 */
class LogLoginSearch extends LogLogin
{
    /**
     * {@inheritdoc}
     */
    public $user;
    public $action;
    public $time;
    public $table;
    public function rules()
    {
        return [
            [['id', 'user_id', 'status'], 'integer'],
            [['time', 'ip_address', 'user_agent','user','table','action'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        // $query = LogLogin::find()->select(['log_login.id','log_login.login_time','log_login.ip_address']);
        // $query2 = LogCrud::find()->select(['log_crud.action','log_crud.created_at','log_crud.ip_address']);
        // $query->joinWith(['user']);
        // $query2->joinWith(['user']);
        // $query->union($query2);
        // $query->joinWith(['user']);
        $pageSize = 10; // jumlah baris per halaman
        $pageNumber = 1;
        $query = (new \yii\db\Query())
        ->select(['log_login.id as id','action' => new \yii\db\Expression("'Login'"),'table' => new \yii\db\Expression("'None'"),
        'user.username as user', 'log_login.login_time as time','log_login.ip_address'])
        ->from('log_login')
        ->join('LEFT JOIN', 'user', 'log_login.user_id = user.id')
        ->where(['log_login.status'=>1]);
    
    $query2 = (new \yii\db\Query())
        ->select("log_crud.id as id, log_crud.action as action,log_crud.table as table, user.username as user, log_crud.created_at as time,log_crud.ip_address")
        ->from('log_crud')
        ->join('LEFT JOIN', 'user', 'log_crud.user_id = user.id');
    
    $query = (new \yii\db\Query())
        ->from(['u' => $query->union($query2)])
        ->orderBy(['time'=>SORT_DESC, 'user'=>SORT_ASC])
        ->offset($pageSize * ($pageNumber - 1))
        ->limit($pageSize);
    
        //add limit pagination
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        $dataProvider->sort->attributes['user'] = [
            'asc' => ['user' => SORT_ASC],
            'desc' => ['user' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'ip_address', $this->ip_address])
            ->andFilterWhere(['like', 'user', $this->user])
            ->andFilterWhere(['like', 'action', $this->action])
            ->andFilterWhere(['like', 'time', $this->time])
            ->andFilterWhere(['like', 'user_agent', $this->user_agent]);

        return $dataProvider;
    }
}
