<?php

namespace common\models\queries;

use common\models\ArtworkItem;

/**
 * This is the ActiveQuery class for [[\common\models\ArtworkItemState]].
 *
 * @see \common\models\ArtworkItemState
 */
class ArtworkItemStateQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return \common\models\ArtworkItemState[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\ArtworkItemState|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function under(ArtworkItem $item, $id)
    {
        return $this->andWhere(['item_id' => $item->id])->andWhere(['<=', 'id', $id]);
    }
}