<?php
namespace backend\modules\tracker\models;

use common\models\ArtworkItem;
use common\models\ArtworkItemState;
use yii\base\Model;
use yii\web\UploadedFile;

class ArtworkItemNewForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $file;

    public $comp_id;

    public function rules()
    {
        return [
            [['comp_id'], 'integer'],
            [['comp_id'], 'required']
        ];
    }

    public function save()
    {
        if ($this->file && !$this->file->hasError) {
            $model = new ArtworkItem();
            $model->comp_id = $this->comp_id;
            $model->file_name = $this->file->name;
            $model->name = $this->file->name;
            $model->save();

            $state = new ArtworkItemState();
            $state->item_id = $model->id;
            $state->original_file_name = $this->file->name;
            $state->generateFileName($this->file->name);

            if ($this->file->saveAs($state->getFilePath())) {
                return $state->save();
            } else {
                $model->delete();
            }
        }
        return false;
    }
}
