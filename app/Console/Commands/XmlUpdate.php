<?php

namespace App\Console\Commands;

use App\Jobs\UpdateGettyXmlField;
use App\Enums\GettyVocab;
use App\Models\Agent;
use App\Models\ArtworkType;
use App\Models\Place;
use App\Models\Term;

class XmlUpdate extends AbstractCommand
{
    protected $signature = 'xml:update';

    protected $description = 'Fill any empty XML columns';

    public function handle()
    {
        $this->updateModel(
            modelClass: Agent::class,
            xmlField: 'ulan_xml',
            idField: 'ulan_id',
            gettyVocab: GettyVocab::ULAN,
        );

        $this->updateModel(
            modelClass: ArtworkType::class,
            xmlField: 'aat_xml',
            idField: 'aat_id',
            gettyVocab: GettyVocab::AAT,
        );

        $this->updateModel(
            modelClass: Place::class,
            xmlField: 'tgn_xml',
            idField: 'tgn_id',
            gettyVocab: GettyVocab::TGN,
        );

        $this->updateModel(
            modelClass: Term::class,
            xmlField: 'aat_xml',
            idField: 'aat_id',
            gettyVocab: GettyVocab::AAT,
        );
    }

    private function updateModel(
        string $modelClass,
        string $xmlField,
        string $idField,
        GettyVocab $gettyVocab,
    ) {
        $items = ($modelClass)::query()
            ->whereNotNull($idField)
            ->whereNull($xmlField)
            ->select(['id', $idField])
            ->cursor();

        foreach ($items as $item) {
            $job = new UpdateGettyXmlField(
                modelClass: $modelClass,
                id: $item->id,
                xmlField: $xmlField,
                gettyId: $item->{$idField},
                gettyVocab: $gettyVocab,
            );

            $this->info(sprintf('[%s] %s -> %s',
                $modelClass,
                $item->id,
                $item->{$idField},
            ));

            dispatch($job);
        }
    }
}
