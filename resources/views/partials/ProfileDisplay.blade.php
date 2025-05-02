@if(session('DisplayTable')=='pvt')
<div style='grid-column: span 1; padding:30px; border-left: 1px solid var(--border);border-top: 1px solid var(--border);'>
    <style>
    .chatgrid {
        grid-template-columns: 300px 1fr 400px;
    }

    @media only screen and (max-width: 768px) {
        .chatgrid {
            grid-template-columns: 1fr;
            grid-template-rows: auto 1fr;
        }

        #chat-menu {
            display: none;
        }

    }
    </style>
    <div>
        <a href="/ProfileClose" style="font-size: 24px; float:right; text-Decoration:none;">&#10060;</a>
    </div>
    <div class="profile-header">
        <img src="{{ asset('img/user.png') }}" alt="User Profile Picture">
        <h2>{{ $user->user_id }}</h2>
        <p style="color: #818181">{{ $user->mobile_number ?? 'N/A' }} | {{ $user->email ?? 'N/A' }}</p>
    </div>

    <div class="profile-body">
        <div class="profile-info">
            <h3>ABOUT ME</h3>
            <p><span>Name:</span> {{ $user->full_name ?? 'N/A' }}</p>
            <p><span>User ID:</span> {{ $user->user_id ?? 'N/A' }}</p>
            <p><span>Email:</span> {{ $user->email ?? 'N/A' }}</p>
            <p><span>Phone:</span> {{ $user->mobile_number ?? 'N/A' }}</p>
            <p><span>Gender:</span> {{ $user->gender ?? 'N/A' }}</p>
            <p><span>Date of Birth:</span> {{ $user->dob ? date('F j, Y', strtotime($user->dob)) : 'N/A' }}
            </p>
            <p><span>Joined:</span> {{ date('F j, Y', strtotime($user->created_at)) }}</p>
        </div>
    </div>
</div>
@endif
@if(session('DisplayTable')=='grp')
<div style='grid-column: span 1; padding:30px;border-left: 1px solid var(--border);border-top: 1px solid var(--border);'>
    <style>
    .chatgrid {
        grid-template-columns: 300px 1fr 400px;
    }
    @media only screen and (max-width: 768px) {
        .chatgrid {
            grid-template-columns: 1fr;
            grid-template-rows: auto 1fr;
        }

        #chat-menu {
            display: none;
        }

    }
    </style>
    <a href="/ProfileClose"
        style="font-size: 24px; display: inline-block; float:right; text-Decoration:none;">&#10060;</a>
    <div class="profile-header">
        <img src="{{ asset('img/user.png') }}" alt="User Profile Picture">
        <h2>{{ $user->grp_name }}</h2>
    </div>

    <div class="profile-body">
        <div class="profile-info">
            <h3>Members</h3>
            @php
            $members = json_decode($user->members, true) ?? [];
            @endphp
            <p><span>Name:</span></p>
            @php
            $members = json_decode($user->members, true) ?? [];
            @endphp

            @foreach ($members as $index => $member)
            <form action="{{ route('ProfileDisplayOne') }}" method="POST">
                @csrf
                <input type="hidden" name="id_receivers" value="{{ $member }}">
                <p>
                    <button type="submit"
                        style='background:var(--background); border:none; cursor: pointer; font-size:16px'>
                        <span>Member {{ $index + 1 }} : </span>
                        <span style='color:var(--on-background)'>{{ $member }}</span>
                    </button>
                </p>
            </form>
            @endforeach
        </div>
    </div>
</div>
@endif