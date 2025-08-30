<?php

declare(strict_types=1);

namespace Yulo\Data\Enums;

enum AccessLevelAbilities: string
{
    // =================================================================================================================
    // AI ABILITIES - Features powered by AI
    // =================================================================================================================

    case AI__CONVERSATION_SIMULATION = 'ai:conversation:simulation';             // Simulates a conversation with a student or examiner
    case AI__TEXT_IMPROVEMENT = 'ai:text:improvement';                           // Improvement of grammar, spelling, and punctuation
    case AI__LEARNING_RECOMMENDATIONS = 'ai:learning:recommendations';           // Suggestions for learning based on test results

    // =================================================================================================================
    // MODULE ABILITIES - Core Learning Modules
    // =================================================================================================================

    case MODULE__READING_COMPREHENSION = 'module:reading--comprehension';        // Global, detailed, and selective understanding
    case MODULE__LISTENING_COMPREHENSION = 'module:listening--comprehension';    // Global, detailed, and selective understanding
    case MODULE__LANGUAGE_ELEMENTS = 'module:language--elements';                // Grammar and vocabulary
    case MODULE__WRITTEN_EXPRESSION = 'module:written--expression';              // Writing formats (letters, emails, etc.)
    case MODULE__ORAL_EXPRESSION = 'module:oral--expression';                    // Introduction, Discussion, Collaboration and Problem-solving

    // =================================================================================================================
    // EXAM ABILITIES - Exams
    // =================================================================================================================

    case EXAM__TIMED_MODULE_PRACTICE = 'exam:timed:practice';
    case EXAM__FULL_MODULE_SIMULATION = 'exam:full:simulation';

    // =================================================================================================================
    // MISC ABILITIES - Miscellaneous
    // =================================================================================================================

    case SYSTEM__REFRESH_ACCESS_TOKEN = 'system:refresh-access-token';
    case SYSTEM__REVOKE_ALL_ACCESS_TOKENS = 'system:revoke-all-access-tokens';

    /**
     * @return ($asString is true ? string : string[])
     */
    public static function for(AccessLevel $level, bool $asString = false): array|string
    {
        $abilities = match ($level) {
            AccessLevel::FULL => array_map(fn (self $item) => $item->value, self::forFull()),
            AccessLevel::BASIC => array_map(fn (self $item) => $item->value, self::forBasic()),
            AccessLevel::TRIAL => array_map(fn (self $item) => $item->value, self::forTrial()),
        };

        return $asString ? implode(',', $abilities) : $abilities;
    }

    /**
     * @return \Yulo\Data\Enums\AccessLevelAbilities[]
     */
    private static function forTrial(): array
    {
        return [
            self::AI__TEXT_IMPROVEMENT,
            self::MODULE__READING_COMPREHENSION,
            self::MODULE__LANGUAGE_ELEMENTS,
            self::MODULE__WRITTEN_EXPRESSION,
        ];
    }

    /**
     * @return \Yulo\Data\Enums\AccessLevelAbilities[]
     */
    private static function forFull(): array
    {
        return [
            self::AI__CONVERSATION_SIMULATION,
            self::AI__TEXT_IMPROVEMENT,
            self::AI__LEARNING_RECOMMENDATIONS,
            self::MODULE__READING_COMPREHENSION,
            self::MODULE__LISTENING_COMPREHENSION,
            self::MODULE__LANGUAGE_ELEMENTS,
            self::MODULE__WRITTEN_EXPRESSION,
            self::MODULE__ORAL_EXPRESSION,
            self::EXAM__TIMED_MODULE_PRACTICE,
            self::EXAM__FULL_MODULE_SIMULATION,
        ];
    }

    /**
     * @return \Yulo\Data\Enums\AccessLevelAbilities[]
     */
    private static function forBasic(): array
    {
        return [
            self::AI__TEXT_IMPROVEMENT,
            self::AI__LEARNING_RECOMMENDATIONS,
            self::MODULE__READING_COMPREHENSION,
            self::MODULE__LISTENING_COMPREHENSION,
            self::MODULE__LANGUAGE_ELEMENTS,
            self::MODULE__WRITTEN_EXPRESSION,
            self::MODULE__ORAL_EXPRESSION,             // ⚠️ Without AI conversation simulation, this only returns text
            self::EXAM__TIMED_MODULE_PRACTICE,
            self::EXAM__FULL_MODULE_SIMULATION,
        ];
    }
}
