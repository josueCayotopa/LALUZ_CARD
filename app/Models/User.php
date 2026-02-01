<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;

class User extends Authenticatable
{
    use  HasFactory, Notifiable;

    const TIPO_ADMIN = 'admin';
    const TIPO_RH = 'recursos_humanos';
    const TIPO_ADMIN_AREA = 'admin_area';
    const TIPO_SUPERVISOR = 'supervisor';
    const TIPO_SEGURIDAD = 'seguridad';
    const TIPO_USUARIO = 'usuario';
    const TIPO_SIN_ACCESO = 'sin_acceso';

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'name',
        'usuario',
        'type',               // columna con CHECK (de la BD)
        'type_sis_permiso',   // tu rol interno (admin, seguridad, etc.)
        'area_responsable',
        'email',
        'telephone',          // TELÉFONO
        'number',             // DNI
        'password',
        'active',
        'api_token',
         'IND_GERENTE',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'active' => 'boolean',
    ];
    // App\Models\User.php

    public static function tiposValidos(): array
    {
        return [
            self::TIPO_ADMIN        => 'Administrador',
            self::TIPO_RH           => 'Recursos Humanos',
            self::TIPO_ADMIN_AREA   => 'Admin. de Área',
            self::TIPO_SUPERVISOR   => 'Supervisor',
            self::TIPO_SEGURIDAD    => 'Seguridad',
            self::TIPO_USUARIO      => 'Usuario',
            self::TIPO_SIN_ACCESO   => 'Sin acceso',
        ];
    }
    public function getConnectionName()
    {
        return Session::get('db_connection') ?: Config::get('database.default');
    }

    /** COD_USUARIO del legado (por conveniencia) */
    public function getCodUsuarioAttribute(): ?string
    {
        return $this->usuario; // mismo valor
    }
    public function estaVinculadoMae(): bool
    {
        return (bool) $this->mae?->exists;
    }



    public function usu_personal()
    {
        return $this->belongsTo(MaeUsuario::class, 'usuario', 'COD_USUARIO');
    }

    

   
    // Accessors
    public function getNombrePersonalAttribute()
    {
        return $this->personal ? $this->personal->nombre_completo : 'Sin personal asignado';
    }

    public function getAreaPersonalAttribute()
    {
        return $this->personal && $this->personal->area ? $this->personal->area->DES_AREAS : 'Sin área';
    }

    public function getTipoSisPermisoLabelAttribute()
    {
        return match ($this->type_sis_permiso) {
            self::TIPO_ADMIN => 'Administrador',
            self::TIPO_RH => 'Recursos Humanos',
            self::TIPO_ADMIN_AREA => 'Admin. de Área',
            self::TIPO_SUPERVISOR => 'Supervisor',
            self::TIPO_SEGURIDAD => 'Seguridad',
            self::TIPO_USUARIO => 'Usuario',
            self::TIPO_SIN_ACCESO => 'Sin Acceso',
            default => 'No Definido'
        };
    }

    public function getTipoSisPermisoColorAttribute()
    {
        return match ($this->type_sis_permiso) {
            self::TIPO_ADMIN => 'danger',
            self::TIPO_RH => 'purple',
            self::TIPO_ADMIN_AREA => 'orange',
            self::TIPO_SUPERVISOR => 'warning',
            self::TIPO_SEGURIDAD => 'info',
            self::TIPO_USUARIO => 'success',
            self::TIPO_SIN_ACCESO => 'gray',
            default => 'gray'
        };
    }


    public function sucursalesCodigos(string $empresa): array
    {
        if ($this->esAdminOrRH()) return ['*'];

        $mae = $this->mae_user; // (o $this->usu_personal)
        if (!$mae) return [];

        return $mae->sucursales()
            ->wherePivot('COD_EMPRESA', $empresa)
            ->pluck('MAE_SUCURSAL.COD_SUCURSAL')
            ->map('strval')
            ->all();
    }

    /** Valida acceso del usuario a una sucursal específica */
    public function tieneAccesoASucursal(string $empresa, string $sucursal): bool
    {
        if ($this->esAdminOrRH() || ($this->mae_user?->IND_ADMIN === 'S')) return true;

        $cods = $this->sucursalesCodigos($empresa);
        if (in_array('*', $cods, true)) return true;

        return in_array((string) $sucursal, $cods, true);
    }



    // Métodos de verificación de roles (optimizados)
    public function tieneRol($roles): bool
    {
        if (!is_array($roles)) {
            $roles = [$roles];
        }
        return in_array($this->type_sis_permiso, $roles);
    }

    public function esAdmin(): bool
    {
        return $this->tieneRol(self::TIPO_ADMIN);
    }

    public function esRecursosHumanos(): bool
    {
        return $this->tieneRol(self::TIPO_RH);
    }

    public function esAdminArea(): bool
    {
        return $this->tieneRol(self::TIPO_ADMIN_AREA);
    }
    public function esAdminOrRH(): bool
    {
        return $this->tieneRol([self::TIPO_ADMIN, self::TIPO_RH]);
    }
    public function esSupervisor(): bool
    {
        return $this->tieneRol(self::TIPO_SUPERVISOR);
    }

    public function esSeguridad(): bool
    {
        return $this->tieneRol(self::TIPO_SEGURIDAD);
    }


    public function esUsuarioNormal(): bool
    {
        return $this->tieneRol(self::TIPO_USUARIO);
    }

    // Métodos de permisos (optimizados)
    public function puedeAccederSistemaPermisos(): bool
    {
        return $this->active && !$this->tieneRol(self::TIPO_SIN_ACCESO);
    }


    public function puedeAprobarPermisos(): bool
    {
        return $this->tieneRol([self::TIPO_ADMIN, self::TIPO_RH, self::TIPO_ADMIN_AREA]);
    }

    public function puedeControlarSeguridad(): bool
    {
        return $this->tieneRol([self::TIPO_ADMIN, self::TIPO_SEGURIDAD]);
    }

    public function puedeAprobarPermisosEspeciales(): bool
    {
        return $this->tieneRol([self::TIPO_ADMIN, self::TIPO_RH]);
    }

    public function puedeSolicitarPermisosEspeciales(): bool
    {
        return $this->tieneRol([
            self::TIPO_ADMIN,
            self::TIPO_RH,
            self::TIPO_ADMIN_AREA,
            self::TIPO_SUPERVISOR,
            self::TIPO_USUARIO
        ]);
    }

    public function puedeVerTodosLosPermisos(): bool
    {
        return $this->tieneRol([
            self::TIPO_ADMIN,
            self::TIPO_RH,
            self::TIPO_ADMIN_AREA,
            self::TIPO_SUPERVISOR
        ]);
    }



    // Método para obtener aprobadores disponibles
    public static function aprobadoresDisponibles(): array
    {
        return self::whereIn('type_sis_permiso', [
            self::TIPO_ADMIN,
            self::TIPO_RH,
            self::TIPO_ADMIN_AREA
        ])->active()->get()->mapWithKeys(function ($user) {
            return [$user->id => $user->name . ' (' . $user->tipo_sis_permiso_label . ')'];
        })->toArray();
    }

    // Scope para usuarios activos
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
    public function getTypeSisPermisoAttribute($value)
    {
        // Si en BD está nulo o vacío, forzamos defaults
        if (!$value) {
            // Si el usuario es 'admin', devolver rol administrador
            if ($this->usuario === 'admin') {
                return self::TIPO_ADMIN;
            }
            // Fallback genérico
            return self::TIPO_USUARIO;
        }

        return $value;
    }
       public function esGerente(): bool
    {
        return $this->IND_GERENTE === 'S';
    }

    public function scopeGerente($query)
    {
        return $query->where('IND_GERENTE', 'S')->where('active', true);
    }
}
