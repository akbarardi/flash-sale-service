<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('game:search {A : Langkah ke Utara/Up} {B : Langkah ke Timur/Right} {C : Langkah ke Selatan/Down}')]
#[Description('Mencari kemungkinan koordinat hidden item pada grid matriks')]
class HiddenItemGame extends Command
{
    private array $grid = [
        ['#', '#', '#', '#', '#', '#', '#', '#'],
        ['#', '.', '.', '.', '.', '.', '.', '#'],
        ['#', '.', '#', '#', '#', '.', '.', '#'],
        ['#', '.', '.', '.', '#', '.', '#', '#'],
        ['#', 'X', '#', '.', '.', '.', '.', '#'],
        ['#', '#', '#', '#', '#', '#', '#', '#'],
    ];

    public function handle()
    {
        $stepsA = (int) $this->argument('A');
        $stepsB = (int) $this->argument('B');
        $stepsC = (int) $this->argument('C');
        $currentRow = 4;
        $currentCol = 1;

        $this->info("Posisi Awal Player (X): Baris {$currentRow}, Kolom {$currentCol}");
        $this->info("Rencana Pergerakan: Utara ({$stepsA}) -> Timur ({$stepsB}) -> Selatan ({$stepsC})");

        for ($i = 0; $i < $stepsA; $i++) {
            $currentRow--;
            if ($this->isObstacle($currentRow, $currentCol)) {
                $this->error("Pergerakan gagal: Menabrak obstacle di koordinat ({$currentRow}, {$currentCol}) saat bergerak ke Utara.");
                return Command::FAILURE;
            }
        }

        for ($i = 0; $i < $stepsB; $i++) {
            $currentCol++;
            if ($this->isObstacle($currentRow, $currentCol)) {
                $this->error("Pergerakan gagal: Menabrak obstacle di koordinat ({$currentRow}, {$currentCol}) saat bergerak ke Timur.");
                return Command::FAILURE;
            }
        }

        for ($i = 0; $i < $stepsC; $i++) {
            $currentRow++;
            if ($this->isObstacle($currentRow, $currentCol)) {
                $this->error("Pergerakan gagal: Menabrak obstacle di koordinat ({$currentRow}, {$currentCol}) saat bergerak ke Selatan.");
                return Command::FAILURE;
            }
        }

        if ($this->grid[$currentRow][$currentCol] === '.') {
            $this->newLine();
            $this->info("========================================");
            $this->info("Kandidat koordinat ditemukan!");
            $this->line("Titik Koordinat Array [Baris][Kolom]: [{$currentRow}][{$currentCol}]");
            $this->info("========================================");

            $this->newLine();
            $this->info("Tampilan Grid (Item ditandai dengan $):");
            $this->displayGridWithItem($currentRow, $currentCol);
        } else {
            $this->error("Titik akhir tidak valid atau bukan jalur aman.");
        }

        return Command::SUCCESS;
    }

    private function isObstacle($row, $col): bool
    {
        if ($row < 0 || $row >= count($this->grid) || $col < 0 || $col >= count($this->grid[0])) {
            return true;
        }
        return $this->grid[$row][$col] === '#';
    }

    private function displayGridWithItem($itemRow, $itemCol)
    {
        foreach ($this->grid as $rowIndex => $row) {
            $lineString = "";
            foreach ($row as $colIndex => $char) {
                if ($rowIndex === $itemRow && $colIndex === $itemCol) {
                    $lineString .= "$";
                } else {
                    $lineString .= $char;
                }
            }
            $this->line($lineString);
        }
    }
}
