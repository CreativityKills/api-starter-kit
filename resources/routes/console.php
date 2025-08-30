<?php

use Laravel\Sanctum\Console\Commands\PruneExpired;

Schedule::command(PruneExpired::class, ['--hours' => 48])->daily();
