<?php

namespace App\Exports;

use App\Models\Categoria;
use App\Models\UnidadMedida;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class ProductosImportTemplate implements FromCollection, WithHeadings, WithEvents
{
    public function collection()
    {
        // Retornar una fila vacía como ejemplo
        return collect([
            [
                'Producto de Ejemplo',
                100.00,
                80.00,
                10,
                'Si',
                '12345678',
                120.00,
                90.00,
                100.00,
                'PROD001',
                'Descripción del producto',
                '', // Categoría - se llenará con dropdown
                'Descripción adicional',
                ''  // Unidad - se llenará con dropdown
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'Nombre',                    // A
            'Precio',                    // B
            'Costo',                     // C
            'Cantidad',                  // D
            'Afecto ICBP',              // E
            'Código SUNAT',             // F
            'Precio Mayorista',         // G
            'Precio Distribuidor',      // H
            'Precio Unidad',            // I
            'Código',                   // J (Indispensable)
            'Detalle',                  // K
            'Categoría',                // L
            'Descripción',              // M
            'Unidad de Medida'          // N
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                $phpSpreadsheet = $sheet->getDelegate();

                // Obtener categorías y unidades
                $categorias = Categoria::pluck('nombre')->toArray();
                $unidades = UnidadMedida::pluck('nombre')->toArray();

                // Crear hojas auxiliares para los dropdowns
                $workbook = $phpSpreadsheet->getParent();
                
                // Hoja para categorías
                $categoriasSheet = $workbook->createSheet();
                $categoriasSheet->setTitle('Categorias');
                foreach ($categorias as $index => $categoria) {
                    $categoriasSheet->setCellValue('A' . ($index + 1), $categoria);
                }

                // Hoja para unidades
                $unidadesSheet = $workbook->createSheet();
                $unidadesSheet->setTitle('Unidades');
                foreach ($unidades as $index => $unidad) {
                    $unidadesSheet->setCellValue('A' . ($index + 1), $unidad);
                }

                // Aplicar dropdown a columna L (Categoría) - filas 2 a 1000
                $validation = $phpSpreadsheet->getCell('L2')->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validation->setAllowBlank(true);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setErrorTitle('Error de entrada');
                $validation->setError('El valor no está en la lista');
                $validation->setPromptTitle('Seleccionar Categoría');
                $validation->setPrompt('Seleccione una categoría de la lista');
                $validation->setFormula1('Categorias!$A$1:$A$' . count($categorias));

                // Copiar validación a todas las filas de la columna L
                for ($row = 2; $row <= 1000; $row++) {
                    $phpSpreadsheet->getCell('L' . $row)->setDataValidation(clone $validation);
                }

                // Aplicar dropdown a columna N (Unidad) - filas 2 a 1000
                $validationUnidad = $phpSpreadsheet->getCell('N2')->getDataValidation();
                $validationUnidad->setType(DataValidation::TYPE_LIST);
                $validationUnidad->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validationUnidad->setAllowBlank(true);
                $validationUnidad->setShowInputMessage(true);
                $validationUnidad->setShowErrorMessage(true);
                $validationUnidad->setShowDropDown(true);
                $validationUnidad->setErrorTitle('Error de entrada');
                $validationUnidad->setError('El valor no está en la lista');
                $validationUnidad->setPromptTitle('Seleccionar Unidad');
                $validationUnidad->setPrompt('Seleccione una unidad de la lista');
                $validationUnidad->setFormula1('Unidades!$A$1:$A$' . count($unidades));

                // Copiar validación a todas las filas de la columna N
                for ($row = 2; $row <= 1000; $row++) {
                    $phpSpreadsheet->getCell('N' . $row)->setDataValidation(clone $validationUnidad);
                }

                // Aplicar dropdown a columna E (Afecto ICBP) - opciones Si/No
                $validationIcbp = $phpSpreadsheet->getCell('E2')->getDataValidation();
                $validationIcbp->setType(DataValidation::TYPE_LIST);
                $validationIcbp->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validationIcbp->setAllowBlank(true);
                $validationIcbp->setShowInputMessage(true);
                $validationIcbp->setShowErrorMessage(true);
                $validationIcbp->setShowDropDown(true);
                $validationIcbp->setErrorTitle('Error de entrada');
                $validationIcbp->setError('Debe seleccionar Si o No');
                $validationIcbp->setPromptTitle('Afecto ICBP');
                $validationIcbp->setPrompt('Seleccione Si o No');
                $validationIcbp->setFormula1('"Si,No"');

                // Copiar validación a todas las filas de la columna E
                for ($row = 2; $row <= 1000; $row++) {
                    $phpSpreadsheet->getCell('E' . $row)->setDataValidation(clone $validationIcbp);
                }

                // Formato de encabezados
                $sheet->getStyle('A1:N1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'color' => ['rgb' => '366092']
                    ],
                    'font' => [
                        'color' => ['rgb' => 'FFFFFF'],
                        'bold' => true
                    ]
                ]);

                // Ajustar ancho de columnas
                foreach (range('A', 'N') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Ocultar hojas auxiliares
                $categoriasSheet->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);
                $unidadesSheet->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);
            }
        ];
    }
}