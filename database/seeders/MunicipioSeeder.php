<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Municipio;
use App\Models\Departamento;

class MunicipioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $municipios = [
            'Francisco Morazán' => [
                'Tegucigalpa',
                'Valle de Ángeles',
                'Santa Lucía',
                'Talanga',
                'Cedros',
                'Lepaterique',
                'Guachapali'
            ],
            'Cortés' => [
                'San Pedro Sula',
                'Choloma',
                'Omoa',
                'Potrerillos',
                'Puerto Cortés',
                'La Lima',
                'Villanueva'
            ],
            'Atlántida' => [
                'La Ceiba',
                'El Porvenir',
                'Esparta',
                'Juticlapa',
                'La Masica',
                'San Francisco',
                'Tela'
            ],
            'Choluteca' => [
                'Choluteca',
                'Apacilagua',
                'Concepción',
                'Duyure',
                'El Corpus',
                'Marcovia',
                'Morolika',
                'Namasigüe',
                'Palmar',
                'San Isidro',
                'San José'
            ],
            'Yoro' => [
                'Yoro',
                'Arenal',
                'Camasca',
                'Comayagua',
                'Dolores',
                'El Paraíso',
                'Jocón',
                'Morazán',
                'Olanchito',
                'Santa Rita',
                'Sonaguera',
                'Sulaco'
            ],
            'Olancho' => [
                'Juticalpa',
                'Catacamas',
                'Dulce Nombre',
                'Gualaco',
                'Guata',
                'Guarizama',
                'Jano',
                'La Unión',
                'Manto',
                'San Esteban',
                'Silca'
            ],
            'Comayagua' => [
                'Comayagua',
                'Ajuterique',
                'El Paraíso',
                'Humuya',
                'La Libertad',
                'Lamaní',
                'Lejanías',
                'Meámbar',
                'Micihacán',
                'Palmar',
                'San Jerónimo',
                'San José de Comayagua',
                'Santa Clara',
                'Tambla',
                'Taulabé'
            ],
            'Santa Bárbara' => [
                'Santa Bárbara',
                'Arada',
                'Azacualpa',
                'Ceguaca',
                'Concepción',
                'Gualala',
                'Macuelizo',
                'Naranjito',
                'Nueva Celilac',
                'Quimistán',
                'San Luis',
                'San Nicolás'
            ],
            'Lempira' => [
                'Gracias',
                'Belén Gualcho',
                'Calixtlahuaca',
                'Erandique',
                'Gualcince',
                'La Encarnación',
                'La Unión',
                'Las Flores',
                'Mapulaca',
                'Piraera',
                'San Juan Guarita',
                'San Manuel Colohete',
                'San Rafael',
                'San Sebastián',
                'Santa Rosa de Copán',
                'Talgua',
                'Tambla',
                'Tomalá',
                'Valladolid',
                'Virginia'
            ],
            'Copán' => [
                'Santa Rosa de Copán',
                'Cabañas',
                'Concepción',
                'Corquín',
                'Cucuyagua',
                'Dulce Nombre',
                'Filadélfia',
                'La Jigua',
                'La Unión',
                'Nueva Arcadia',
                'Nueva Rosa',
                'San Antonio',
                'San Jerónimo',
                'San Juan de Opoa',
                'Santa Rita',
                'Veracruz'
            ],
            'Ocotepeque' => [
                'Ocotepeque',
                'Belén Gualcho',
                'Fraternidad',
                'La Encarnación',
                'La Unión',
                'Mercedes Cortés',
                'Sensenti',
                'Sinuapa'
            ],
            'Intibucá' => [
                'La Esperanza',
                'Camasca',
                'Colomoncagua',
                'Concepción',
                'Intibucá',
                'Magdalena',
                'Masaguara',
                'San Isidro',
                'San Juan',
                'San Marcos',
                'Santa Lucia',
                'Yamaranguila'
            ],
            'El Paraíso' => [
                'Yuscarán',
                'Alauca',
                'Anapala',
                'Danlí',
                'El Paraíso',
                'Güinope',
                'Jacaleapa',
                'Jamastran',
                'Langue',
                'Mapachique',
                'Montecristo',
                'Ojo de Agua',
                'San Lucas',
                'San Matías',
                'Teupasenti',
                'Texiguat',
                'Vado Ancho',
                'Waslala'
            ],
            'Colón' => [
                'Trujillo',
                'Balfate',
                'Cristóbal Colón',
                'Guanaja',
                'La Ceiba',
                'Limón',
                'Name',
                'Nueva Guadelupe',
                'Sonaguera',
                'Tocoa',
                'Utila'
            ],
            'Islas de la Bahía' => [
                'Roatán',
                'Guanaja',
                'Utila',
                'José María Flores'
            ],
            'La Paz' => [
                'La Paz',
                'Apacilagua',
                'Arcela',
                'Cuyamelito',
                'El Corpus',
                'Guanacaure',
                'Lauterique',
                'Mercedes Cortés',
                'Opatoro',
                'San Antonio Flores',
                'San Juan',
                'San Pedro Tutule',
                'Santa Ana',
                'Santa Elena',
                'Santa María',
                'Yarula'
            ],
            'Gracias a Dios' => [
                'Puerto Lempira',
                'Brus Laguna',
                'Kraukira',
                'Provenica',
                'Raista',
                'Wampusirpi'
            ]
        ];

        foreach ($municipios as $nombreDepto => $munis) {
            $departamento = Departamento::firstWhere('nombre', $nombreDepto);
            
            if ($departamento) {
                foreach ($munis as $nombre) {
                    Municipio::updateOrCreate(
                        ['nombre' => $nombre, 'departamento_id' => $departamento->id],
                        ['nombre' => $nombre, 'departamento_id' => $departamento->id]
                    );
                }
            }
        }
    }
}