<?php

namespace Database\Seeders;

use App\Models\Criterion;
use App\Models\Indicator;
use App\Models\ReportingPeriod;
use App\Models\Standard;
use App\Models\Theme;
use Illuminate\Database\Seeder;

class StandardSeeder extends Seeder
{
    public function run(): void
    {
        $period2025 = ReportingPeriod::where('slug', '2025')->firstOrFail();

        // Standaarden per thema met bijbehorende criteria en indicatoren
        $structuur = [
            'OP' => [
                [
                    'code' => 'OP.1', 'name' => 'Didactisch handelen', 'description' => 'De docent past effectieve didactische werkvormen toe.',
                    'criteria' => [
                        ['number' => 1, 'text' => 'De docent stemt de instructie af op de leerbehoeften van studenten.',
                         'indicators' => ['Differentieert in instructieniveau', 'Past activerende werkvormen toe', 'Geeft heldere uitleg']],
                        ['number' => 2, 'text' => 'De docent bevordert actieve betrokkenheid van studenten.',
                         'indicators' => ['Stelt open vragen', 'Stimuleert samenwerking', 'Geeft ruimte voor inbreng']],
                        ['number' => 3, 'text' => 'De docent maakt gebruik van feedback om het leren te bevorderen.',
                         'indicators' => ['Geeft constructieve feedback', 'Controleert begrip', 'Past instructie aan op basis van feedback']],
                    ],
                ],
                [
                    'code' => 'OP.2', 'name' => 'Leerklimaat', 'description' => 'De docent creëert een positief en veilig leerklimaat.',
                    'criteria' => [
                        ['number' => 1, 'text' => 'Er heerst een respectvolle omgang in de klas.',
                         'indicators' => ['Studenten spreken elkaar respectvol aan', 'Docent modelleert respectvol gedrag']],
                        ['number' => 2, 'text' => 'Studenten durven fouten te maken en vragen te stellen.',
                         'indicators' => ['Fouten worden besproken als leermiddel', 'Vragen worden serieus genomen']],
                        ['number' => 3, 'text' => 'De docent heeft hoge verwachtingen van alle studenten.',
                         'indicators' => ['Verwachtingen worden duidelijk gecommuniceerd', 'Studenten worden uitgedaagd']],
                    ],
                ],
            ],
            'VS' => [
                [
                    'code' => 'VS.1', 'name' => 'Sociale veiligheid', 'description' => 'De school borgt de sociale veiligheid van studenten en medewerkers.',
                    'criteria' => [
                        ['number' => 1, 'text' => 'Er is een actueel anti-pestprotocol aanwezig.',
                         'indicators' => ['Protocol is beschikbaar', 'Studenten kennen het protocol', 'Protocol wordt nageleefd']],
                        ['number' => 2, 'text' => 'Incidenten worden structureel geregistreerd en opgevolgd.',
                         'indicators' => ['Registratiesysteem is aanwezig', 'Incidenten worden besproken', 'Opvolging wordt geborgd']],
                    ],
                ],
                [
                    'code' => 'VS.2', 'name' => 'Fysieke veiligheid', 'description' => 'De schoolomgeving voldoet aan de eisen van fysieke veiligheid.',
                    'criteria' => [
                        ['number' => 1, 'text' => 'De schoollocatie voldoet aan BHV-vereisten.',
                         'indicators' => ['BHV\'ers zijn aanwezig', 'Ontruimingsplan is actueel', 'Oefeningen worden gehouden']],
                        ['number' => 2, 'text' => 'Risico-inventarisaties worden periodiek uitgevoerd.',
                         'indicators' => ['RI&E is aanwezig', 'Aanbevelingen worden opgevolgd']],
                    ],
                ],
            ],
            'BA' => [
                [
                    'code' => 'BA.1', 'name' => 'Toetsing en examinering', 'description' => 'Toetsing is valide, betrouwbaar en transparant.',
                    'criteria' => [
                        ['number' => 1, 'text' => 'Toetsen zijn afgestemd op de vastgestelde leerdoelen.',
                         'indicators' => ['Toetsmatrijs is aanwezig', 'Leerdoelen zijn geborgd in toets', 'Cesuur is onderbouwd']],
                        ['number' => 2, 'text' => 'Studenten zijn vooraf geïnformeerd over de toetseisen.',
                         'indicators' => ['Toetsinformatie is beschikbaar', 'Studenten kennen de criteria']],
                        ['number' => 3, 'text' => 'Toetsresultaten worden geanalyseerd en teruggekoppeld.',
                         'indicators' => ['Resultaatanalyse wordt gemaakt', 'Terugkoppeling vindt plaats', 'Verbeteracties worden ingezet']],
                    ],
                ],
                [
                    'code' => 'BA.2', 'name' => 'Diplomering', 'description' => 'De diplomering verloopt correct en conform regelgeving.',
                    'criteria' => [
                        ['number' => 1, 'text' => 'Diplomabesluiten zijn correct en volledig gedocumenteerd.',
                         'indicators' => ['Besluiten zijn traceerbaar', 'Documentatie is compleet']],
                        ['number' => 2, 'text' => 'Examencommissie functioneert aantoonbaar.',
                         'indicators' => ['Vergaderingen worden gehouden', 'Notulen zijn beschikbaar', 'Besluiten worden nageleefd']],
                    ],
                ],
            ],
            'OR' => [
                [
                    'code' => 'OR.1', 'name' => 'Studieresultaten', 'description' => 'Studenten behalen de gestelde resultaten.',
                    'criteria' => [
                        ['number' => 1, 'text' => 'De doorstroom- en diplomapercentages zijn op niveau.',
                         'indicators' => ['Resultaten worden bijgehouden', 'Vergelijking met norm is gemaakt', 'Verbeteracties zijn ingezet']],
                        ['number' => 2, 'text' => 'Achterblijvende studenten worden tijdig gesignaleerd en begeleid.',
                         'indicators' => ['Signalering is geborgd', 'Begeleidingstraject is beschikbaar', 'Effectiviteit wordt gemeten']],
                    ],
                ],
                [
                    'code' => 'OR.2', 'name' => 'Tevredenheid', 'description' => 'Studenten en medewerkers zijn tevreden over de opleiding.',
                    'criteria' => [
                        ['number' => 1, 'text' => 'Studenttevredenheid wordt periodiek gemeten.',
                         'indicators' => ['Enquête wordt afgenomen', 'Resultaten worden geanalyseerd', 'Verbeteringen worden doorgevoerd']],
                        ['number' => 2, 'text' => 'Medewerkerstevredenheid wordt periodiek gemeten.',
                         'indicators' => ['Medewerkersonderzoek is aanwezig', 'Resultaten worden besproken']],
                    ],
                ],
            ],
            'SKA' => [
                [
                    'code' => 'SKA.1', 'name' => 'Kwaliteitscyclus', 'description' => 'De school voert een aantoonbare kwaliteitscyclus uit.',
                    'criteria' => [
                        ['number' => 1, 'text' => 'Er is een jaarlijkse plannings- en evaluatiecyclus.',
                         'indicators' => ['Jaarplan is aanwezig', 'Evaluatie vindt jaarlijks plaats', 'Resultaten worden geborgd']],
                        ['number' => 2, 'text' => 'Verbeteracties worden systematisch opgepakt en opgevolgd.',
                         'indicators' => ['Actiepunten zijn geregistreerd', 'Voortgang wordt bewaakt', 'Effectiviteit wordt gemeten']],
                        ['number' => 3, 'text' => 'Kwaliteitsresultaten worden gedeeld met het team.',
                         'indicators' => ['Teambespreking vindt plaats', 'Resultaten zijn transparant']],
                    ],
                ],
                [
                    'code' => 'SKA.2', 'name' => 'Leiderschap', 'description' => 'De leidinggevende stuurt aantoonbaar op kwaliteit.',
                    'criteria' => [
                        ['number' => 1, 'text' => 'De teamleider voert regelmatig gesprekken over kwaliteit.',
                         'indicators' => ['Gesprekken zijn aantoonbaar', 'Kwaliteit staat op de agenda']],
                        ['number' => 2, 'text' => 'Er is een gedeelde visie op kwaliteit binnen het team.',
                         'indicators' => ['Visie is beschreven', 'Team is betrokken bij visie', 'Visie is actueel']],
                    ],
                ],
            ],
        ];

        foreach ($structuur as $themeCode => $standards) {
            $theme = Theme::where('code', $themeCode)->firstOrFail();

            foreach ($standards as $stdData) {
                $standard = Standard::create([
                    'theme_id'            => $theme->id,
                    'reporting_period_id' => $period2025->id,
                    'code'                => $stdData['code'],
                    'name'                => $stdData['name'],
                    'description'         => $stdData['description'],
                ]);

                foreach ($stdData['criteria'] as $critData) {
                    $criterion = Criterion::create([
                        'standard_id'         => $standard->id,
                        'reporting_period_id' => $period2025->id,
                        'number'              => $critData['number'],
                        'text'                => $critData['text'],
                    ]);

                    foreach ($critData['indicators'] as $sort => $indicatorText) {
                        Indicator::create([
                            'criterion_id'        => $criterion->id,
                            'reporting_period_id' => $period2025->id,
                            'text'                => $indicatorText,
                            'sort_order'          => $sort + 1,
                        ]);
                    }
                }
            }
        }
    }
}
