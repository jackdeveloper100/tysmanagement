<?php
namespace backend\modules\core\models;

use common\models\User;
use yii\base\Model;

class ProfileForm extends Model
{
    /**
     * @var User
     */
    private $model;

    public $new_password;
    public $slack_username;
    public $phone;
    public $cell;

    public function rules()
    {
        return [
            [['slack_username'], 'required'],
            [['phone'], 'string', 'max' => 64],
            [['cell'], 'string', 'max' => 10],
            [['slack_username'], 'string', 'max' => 255],
            [['new_password'], 'string', 'min' => 5, 'skipOnEmpty' => true],
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            $this->model->slack_username = $this->slack_username;
            $this->model->phone = $this->phone;
            $this->model->cell = $this->cell;

            if (!empty($this->new_password)) {
                $this->model->setPassword($this->new_password);
            }
            return $this->model->save();
        }
        return false;
    }

    /**
     * @param User $model
     */
    public function setModel(User $model)
    {
        $this->model = $model;

        $this->slack_username = $model->slack_username;
        $this->phone = $model->phone;
        $this->cell = $model->cell;
    }
}
