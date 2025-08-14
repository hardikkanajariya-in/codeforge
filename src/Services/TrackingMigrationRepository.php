<?php

namespace HkDevs\CodeForgeStudio\Services;

use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Support\Facades\Log;

/**
 * TrackingMigrationRepository
 * 
 * A wrapper around Laravel's DatabaseMigrationRepository that provides
 * enhanced migration tracking capabilities for CodeForge Database Studio.
 * 
 * This class intercepts migration repository operations to provide
 * real-time tracking of migration execution, rollbacks, and state changes.
 * 
 * @package HkDevs\CodeForgeStudio\Services
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 */
class TrackingMigrationRepository extends DatabaseMigrationRepository
{
    protected $originalRepository;
    protected MigrationTrackingService $trackingService;

    public function __construct($originalRepository, MigrationTrackingService $trackingService)
    {
        $this->originalRepository = $originalRepository;
        $this->trackingService = $trackingService;

        // Copy necessary properties from the original repository
        $this->resolver = $originalRepository->resolver;
        $this->table = $originalRepository->table;
    }

    /**
     * Log that a migration was run.
     *
     * @param  string  $file
     * @param  int  $batch
     * @return void
     */
    public function log($file, $batch)
    {
        // Call the original method first
        $this->originalRepository->log($file, $batch);

        // Track the migration execution
        $this->trackingService->logMigrationExecution(
            $file,
            'migrate',
            0, // Execution time tracking could be added in future versions
            'success'
        );
    }

    /**
     * Remove a migration from the log.
     *
     * @param  object  $migration
     * @return void
     */
    public function delete($migration)
    {
        // Track the rollback
        $this->trackingService->logMigrationExecution(
            $migration->migration ?? 'unknown',
            'rollback',
            0, // Execution time tracking could be added in future versions
            'success'
        );

        // Call the original method
        return $this->originalRepository->delete($migration);
    }

    /**
     * Delegate all other method calls to the original repository
     */
    public function __call($method, $parameters)
    {
        return $this->originalRepository->$method(...$parameters);
    }

    /**
     * Delegate property access to the original repository
     */
    public function __get($property)
    {
        return $this->originalRepository->$property;
    }

    /**
     * Delegate property setting to the original repository
     */
    public function __set($property, $value)
    {
        $this->originalRepository->$property = $value;
    }
}
