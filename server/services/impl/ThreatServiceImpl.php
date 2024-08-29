<?php

namespace app\services\impl;

interface ThreatServiceImpl
{
    function setThreat(array $data, $file): string;
    function getThreats(): string;
    function getThreatsCount(): int;
    function getMyThreatsCount(int $_id): int;
    function getThreatBySlug(string $_slug): string;
    function getReporterThreat(int $_id): string;
    function editThreat(array $data, $file): string;
    // function removeService(string $images, int $id): string;
}
