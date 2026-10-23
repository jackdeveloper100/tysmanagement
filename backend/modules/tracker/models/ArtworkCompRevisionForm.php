<?php
namespace backend\modules\tracker\models;

use common\models\ArtworkItemState;
use yii\base\Model;
use yii\web\UploadedFile;

class ArtworkCompRevisionForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $file;
    public $item_id;

    /**
     * @var ArtworkItemState
     */
    public $model;

    public function setModel(ArtworkItemState $model)
    {
        $this->model = $model;
        $this->setAttributes($model->getAttributes());
    }

    public function rules()
    {
        return [
            [['item_id'], 'integer']
        ];
    }

    public function save()
    {
        if ($this->file && !$this->file->hasError) {
            $this->model->item_id = $this->item_id;
            $this->model->original_file_name = $this->file->name;
            $this->model->generateFileName($this->file->name);

            if ($this->file->saveAs($this->model->getFilePath())) {
                return $this->model->save();
            }
        }
        return false;
    }
}
