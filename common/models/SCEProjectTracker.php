<?php

namespace common\models;

use yii\helpers\ArrayHelper;

class SCEProjectTracker
{
    public static function getJobs()
    {
        if (getenv('SCE_PROJECT_TRACKER_LOOKKUP_ENABLED')) {
            if (!\Yii::$app->cache->exists('sce_jobs')) {
                $c = new \GuzzleHttp\Client(['base_uri' => getenv('SCE_PROJECT_TRACKER_URL')]);
                $r = $c->get('/api/jobs', [
                    'query' => [
                        'access-token' => getenv('SCE_PROJECT_TRACKER_TOKEN'),
                        'fields' => 'id,legacy_id,name,approver',
                        'per-page' => 99999,
                        'expand' => 'creator'
                    ],
                    'headers' => [
                        'Accept' => 'application/json'
                    ]
                ]);
                $cache = ArrayHelper::map(json_decode($r->getBody()->getContents()), 'id', function ($item) {
                    return $item;
                });
                \Yii::$app->cache->set('sce_jobs', $cache, 60);
                return $cache;
            } else {
                return \Yii::$app->cache->get('sce_jobs');
            }
        } else {
            return [];
        }
    }

    public static function getCostCenters($sce_job_number)
    {
        if (getenv('SCE_PROJECT_TRACKER_LOOKKUP_ENABLED')) {
            if (!\Yii::$app->cache->exists('sce_cost_centers:' . $sce_job_number)) {
                $c = new \GuzzleHttp\Client(['base_uri' => getenv('SCE_PROJECT_TRACKER_URL')]);
                $r = $c->get('/api/jobs/' . $sce_job_number . '/cost-centers', [
                    'query' => [
                        'access-token' => getenv('SCE_PROJECT_TRACKER_TOKEN'),
                        'fields' => 'id,cost_center,percent,approver',
                        'per-page' => 1000
                    ],
                    'headers' => [
                        'Accept' => 'application/json'
                    ]
                ]);
                $cache = ArrayHelper::map(json_decode($r->getBody()->getContents()), 'id', function ($item) {
                    return $item->cost_center . ' - ' . $item->percent . '%';
                });
                \Yii::$app->cache->set('sce_cost_centers:' . $sce_job_number, $cache, 60);
                return $cache;
            } else {
                return \Yii::$app->cache->get('sce_cost_centers:' . $sce_job_number);
            }
        } else {
            return [];
        }
    }
}
