<?php

namespace App\Exports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ProductosExport implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {


        return Producto::with(['categoriaRelacion', 'unidadRelacion'])
            ->activos()
            ->almacenPrincipal()
            ->get()
            ->map(function ($producto) {
                return [
                    'codigo' => $producto->codigo,
                    'cod_barra' => $producto->cod_barra,
                    'nombre' => $producto->nombre,
                    'categoria' => $producto->categoriaRelacion ? $producto->categoriaRelacion->nombre : 'Sin categoría',
                    'unidad' => $producto->unidadRelacion ? $producto->unidadRelacion->nombre : 'Sin unidad',
                    'precio' => $producto->precio,
                    'precio_menor' => $producto->precio_menor,
                    'precio_mayor' => $producto->precio_mayor,
                    'costo' => $producto->costo,
                    'cantidad' => $producto->cantidad,
                    'codsunat' => $producto->codsunat,
                    'descripcion' => $producto->detalle,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Código',
            'Código de Barras',
            'Nombre del Producto',
            'Categoría',
            'Unidad de Medida',
            'Precio Público',
            'Precio Distribuidor',
            'Precio Mayorista',
            'Costo',
            'Stock Actual',
            'Código SUNAT',
            'Descripción'
        ];
    }

    public function startCell(): string
    {
        return 'A6'; // Los datos empezarán en la fila 6
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo para los encabezados (fila 6)
            6 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 12
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Configurar el título principal
                $sheet->setCellValue('A1', 'REPORTE DE PRODUCTOS');
                $sheet->mergeCells('A1:L1');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'color' => ['rgb' => '2F4F4F']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ]
                ]);

                // Fecha de generación
                $sheet->setCellValue('A3', 'Fecha de generación: ' . now()->format('d/m/Y H:i:s'));
                $sheet->mergeCells('A3:L3');
                $sheet->getStyle('A3')->applyFromArray([
                    'font' => [
                        'italic' => true,
                        'size' => 10,
                        'color' => ['rgb' => '666666']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ]
                ]);

                // Ajustar el ancho de las columnas
                $sheet->getColumnDimension('A')->setWidth(12); // Código
                $sheet->getColumnDimension('B')->setWidth(15); // Código de Barras
                $sheet->getColumnDimension('C')->setWidth(35); // Nombre
                $sheet->getColumnDimension('D')->setWidth(20); // Categoría
                $sheet->getColumnDimension('E')->setWidth(20); // Unidad (más ancha)
                $sheet->getColumnDimension('F')->setWidth(12); // Precio
                $sheet->getColumnDimension('G')->setWidth(18); // Precio Distribuidor (más ancha)
                $sheet->getColumnDimension('H')->setWidth(18); // Precio Mayorista (más ancha)
                $sheet->getColumnDimension('I')->setWidth(12); // Costo
                $sheet->getColumnDimension('J')->setWidth(10); // Stock
                $sheet->getColumnDimension('K')->setWidth(15); // Código SUNAT
                $sheet->getColumnDimension('L')->setWidth(30); // Descripción

                // Aplicar bordes a toda la tabla de datos
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle('A6:L' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'CCCCCC']
                        ]
                    ]
                ]);

                // Alinear código y código de barras a la izquierda
                $sheet->getStyle('A7:B' . $lastRow)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT);

                // Alternar colores de filas para mejor legibilidad
                for ($row = 7; $row <= $lastRow; $row++) {
                    if ($row % 2 == 0) {
                        $sheet->getStyle('A' . $row . ':L' . $row)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F8F9FA']
                            ]
                        ]);
                    }
                }

                // Formato de números para las columnas de precios
                $sheet->getStyle('F7:I' . $lastRow)->getNumberFormat()
                    ->setFormatCode('_("S/."* #,##0.00_);_("S/."* \(#,##0.00\);_("S/."* "-"??_);_(@_)');

                // Centrar el contenido de la columna de stock
                $sheet->getStyle('J7:J' . $lastRow)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                     // Alinear código y código de barras a la izquierda (asegurar formato texto)
                $sheet->getStyle('A7:A' . $lastRow)->getNumberFormat()->setFormatCode('@');
                $sheet->getStyle('B7:B' . $lastRow)->getNumberFormat()->setFormatCode('@');

                // Configurar el nombre de la hoja
                $sheet->setTitle('Lista de Productos');
            },
        ];
    }
}
