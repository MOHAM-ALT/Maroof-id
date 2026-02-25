<!-- Role Selector - Circular Icons Only -->
<div class="relative group">
    <!-- Main Icon Button (Circle) -->
    <button 
        class="relative w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold flex items-center justify-center shadow-lg transition-all duration-300 group-hover:scale-110" 
        title="اختر الدور (Select Role)"
        onclick="document.getElementById('roleMenu').classList.toggle('hidden')"
    >
        <!-- Role Initials -->
        <span id="roleInitial" class="text-xs font-bold">{{ substr(Auth::user()->roles->first()->name, 0, 1) }}</span>
    </button>

    <!-- Dropdown Menu - Circular Icons Horizontal -->
    <div 
        id="roleMenu" 
        class="hidden absolute top-12 left-0 bg-white rounded-2xl shadow-2xl p-2 flex flex-row gap-2 border border-gray-200 z-50 animate-in fade-in slide-in-from-top-2"
    >
        @php
            $roles = Auth::user()->roles;
            $roleIcons = [
                'customer' => ['icon' => '👤', 'label' => 'Customer'],
                'super_admin' => ['icon' => '⚙️', 'label' => 'Admin'],
                'print_partner' => ['icon' => '🏭', 'label' => 'Partner'],
                'reseller' => ['icon' => '📦', 'label' => 'Reseller'],
                'designer' => ['icon' => '🎨', 'label' => 'Designer'],
                'affiliate' => ['icon' => '📢', 'label' => 'Affiliate'],
                'business' => ['icon' => '🏢', 'label' => 'Business'],
            ];
        @endphp

        @foreach($roles as $role)
            @php $roleData = $roleIcons[$role->name] ?? ['icon' => '👤', 'label' => 'User']; @endphp
            
            <form method="POST" action="{{ route('switch-role') }}" class="inline">
                @csrf
                <input type="hidden" name="role" value="{{ $role->name }}">
                
                <button 
                    type="submit"
                    class="w-10 h-10 rounded-full flex items-center justify-center text-lg cursor-pointer transition-all duration-200 hover:scale-125 hover:shadow-md"
                    style="background: linear-gradient(135deg, 
                        {{ $role->name === 'customer' ? '#3b82f6, #2563eb' : '' }}
                        {{ $role->name === 'super_admin' ? '#ef4444, #dc2626' : '' }}
                        {{ $role->name === 'print_partner' ? '#f59e0b, #d97706' : '' }}
                        {{ $role->name === 'reseller' ? '#10b981, #059669' : '' }}
                        {{ $role->name === 'designer' ? '#8b5cf6, #7c3aed' : '' }}
                        {{ $role->name === 'affiliate' ? '#06b6d4, #0891b2' : '' }}
                        {{ $role->name === 'business' ? '#ec4899, #be185d' : '' }}
                    )"
                    title="{{ $roleData['label'] }}"
                >
                    {{ $roleData['icon'] }}
                </button>
            </form>
        @endforeach
    </div>
</div>

<!-- Close menu when clicking outside -->
<script>
    document.addEventListener('click', function(event) {
        const roleMenu = document.getElementById('roleMenu');
        const roleButton = event.target.closest('button');
        
        if (!roleButton || !roleButton.contains(document.getElementById('roleInitial'))) {
            if (!roleMenu.contains(event.target)) {
                roleMenu.classList.add('hidden');
            }
        }
    });
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slideInFromTop {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-in {
        animation: fadeIn 0.3s ease-out;
    }
    
    .fade-in {
        animation: fadeIn 0.3s ease-out;
    }
    
    .slide-in-from-top-2 {
        animation: slideInFromTop 0.3s ease-out;
    }
</style>
