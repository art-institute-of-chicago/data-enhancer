<?php

namespace App\Enums;

enum GettyVocab: string
{
    case AAT = 'AAT';
    case ULAN = 'ULAN';
    case TGN = 'TGN';

    public function getBaseUri(): string
    {
        return sprintf('http://vocabsservices.getty.edu/%sService.asmx', $this->value);
    }

    public function getSubjectUri(string|int $gettyId): string
    {
        return sprintf('%s/%sGetSubject?subjectId=%d',
            $this->getBaseUri(),
            $this->value,
            $gettyId,
        );
    }
}
