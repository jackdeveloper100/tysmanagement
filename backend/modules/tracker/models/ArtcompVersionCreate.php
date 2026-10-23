<?php
namespace backend\modules\tracker\models;

use common\models\ArtcompVersion;
use common\models\ArtcompVersionState;
use common\models\ArtworkComp;
use yii\base\Model;

class ArtcompVersionCreate extends Model
{
    public $name;

    private $model;

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string']
        ];
    }

    public function save(ArtworkComp $comp)
    {
        if ($this->validate()) {
            $this->model = new ArtcompVersion();
            $this->model->name = $this->name;
            $this->model->artcomp_id = $comp->id;
            if ($this->model->save()) {
                foreach ($comp->artworkItems as $item) {
                    $m = new ArtcompVersionState();
                    $m->artcomp_version_id = $this->model->id;
                    $m->artwork_item_id = $item->id;
                    $m->artwork_item_state_id = $item->getLatestState()->id;
                    $m->save();
                }
                return true;
            }
        }
        return false;
    }

    /**
     * @return ArtcompVersion
     */
    public function getModel()
    {
        return $this->model;
    }
}
