<!-- Nav items -->
<ul class="navbar-nav">
  <!-- Dashboard -->
  <li class="nav-item">
    <a class="nav-link {{ Request::is('user/dashboard*') ? 'active' : '' }}" href="{{ route('user.dashboard2.index') }}">
      <i class="fi fi-rs-dashboard"></i>
      <span class="nav-link-text">{{ __('Dashboard') }}</span>
    </a>
  </li>

  <!-- WhatsApp Web -->
  @if(isset($pluginConfigs['whatsapp_web']) && $pluginConfigs['whatsapp_web']['status'] === 1 && getUserPlanData('whatsapp_web'))
    @if((int)getUserPlanData('mechanism') === 2)
      @dynamicInclude('WhatsAppWeb', 'nav')
    @elseif((int)getUserPlanData('mechanism') === 1)
      @dynamicInclude('WhatsAppWeb', 'nav_classic')
    @endif

    @if((int)getUserPlanData('mechanism') !== 1 && !Auth::user()->team_id)
      <li class="nav-item">
        <a class="nav-link {{ Request::is('user/cloudapi') || (Request::is('user/cloudapi*') && !Request::is('user/cloudapi/chats/*')) ? 'active' : '' }}" href="{{ route('user.cloudapi.index') }}">
          <i class="fab fa-whatsapp"></i>
          <span class="nav-link-text">{{ __('WhatsApp') }}</span>
          <span class="badge badge-success" style="font-size:8px;">{{__('Official')}}</span>
        </a>
      </li>
      <li class="nav-item" id="whatsappNavItem" style="display: none;">
        <a class="nav-link {{ Request::is('user/cloudapi/chats/*') ? 'active' : '' }}" id="whatsappLink" href="#">
          <i class="fi-rs-comments"></i>
          <span class="nav-link-text">{{ __('Live Chat') }}</span>
        </a>
      </li>
    @endif
  @endif

  <!-- User Notes -->
  @if(isset($pluginConfigs['user_notes']) && $pluginConfigs['user_notes']['status'] === 1 && getUserPlanData('user_notes'))
    <li class="nav-item">
      <a class="nav-link {{ Request::is('user/notes*') ? 'active' : '' }}" href="{{ route('user.notes.index') }}">
        <i class="fi fi-rs-pencil"></i>
        <span class="nav-link-text">{{ __('My Notes') }}</span>
        <span class="badge badge-success" style="font-size:8px;">{{__('Addons')}}</span>
      </a>
    </li>
  @endif

  <!-- Messaging Features -->
  @if(isset($pluginConfigs['whatsapp_web']) && $pluginConfigs['whatsapp_web']['status'] === 1 && getUserPlanData('whatsapp_web') && (int)getUserPlanData('mechanism') !== 1 && !Auth::user()->team_id)
    <li class="nav-item">
      <a class="nav-link {{ Request::is('user/sent-text-message*') ? 'active' : '' }}" href="{{ url('user/sent-text-message') }}">
        <i class="fi fi-rs-paper-plane"></i>
        <span class="nav-link-text">{{ __('Single Send') }}</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ Request::is('user/chatbot*') ? 'active' : '' }}" href="{{ route('user.chatbot.index') }}">
        <i class="fas fa-robot"></i>
        <span class="nav-link-text">{{ __('Auto Reply') }}</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ Request::is('user/apps*') ? 'active' : '' }}" href="{{ route('user.apps.index') }}">
        <i class="fi fi-rs-apps-add"></i>
        <span class="nav-link-text">{{ __('My Apps') }}</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ Request::is('user/bulk-message*') ? 'active' : '' }}" href="{{ url('user/bulk-message') }}">
        <i class="fi fi-rs-rocket-lunch"></i>
        <span class="nav-link-text">{{ __('Bulk Message') }}</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ Request::is('user/schedule-message*') ? 'active' : '' }}" href="{{ url('user/schedule-message') }}">
        <i class="ni ni-calendar-grid-58"></i>
        <span class="nav-link-text">{{ __('Run Campaigns') }}</span>
      </a>
    </li>
  @endif

  <!-- Team Inbox -->
  @if(
    (!isset($pluginConfigs['whatsapp_web']) || $pluginConfigs['whatsapp_web']['status'] === 0) || 
    (isset($pluginConfigs['whatsapp_web']) && $pluginConfigs['whatsapp_web']['status'] === 1 && (int)getUserPlanData('mechanism') !== 1)
  )
    @if(isset($pluginConfigs['team_inbox']) && $pluginConfigs['team_inbox']['status'] === 1 && getUserPlanData('team_inbox'))
      <li class="nav-item">
        <a class="nav-link {{ Request::is('user/team*') ? 'active' : '' }}" href="{{ route('user.team.index') }}">
          <i class="fi fi-rs-users-alt"></i>
          <span class="nav-link-text">{{ __('Team Inbox') }}</span>
          <span class="badge badge-success" style="font-size:8px;">{{__('Addons')}}</span>
        </a>
      </li>
    @endif

    <!-- Webhooks -->
    @if(isset($pluginConfigs['webhooks_payload']) && $pluginConfigs['webhooks_payload']['status'] === 1 && getUserPlanData('webhooks_payload'))
      <li class="nav-item">
        <a class="nav-link {{ Request::is('user/webhooks*') ? 'active' : '' }}" href="{{ url('user/webhooks') }}">
          <i class="ni ni-ui-04"></i>
          <span class="nav-link-text">{{ __('Webhook Logs') }}</span>
          <span class="badge badge-success" style="font-size:8px;">{{__('Addons')}}</span>
        </a>
      </li>
    @endif
  @endif

  <!-- Contacts, Templates, and Logs -->
  @if(!Auth::user()->team_id)
    <li class="nav-item">
      <a class="nav-link {{ Request::is('user/contact*') ? 'active' : '' }}" href="{{ route('user.contact.index') }}">
        <i class="fi fi-rs-address-book"></i>
        <span class="nav-link-text">{{ __('Contacts Book') }}</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ Request::is('user/template*') ? 'active' : '' }}" href="{{ url('user/template') }}">
        <i class="fi fi-rs-template-alt"></i>
        <span class="nav-link-text">{{ __('My Templates') }}</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ Request::is('user/logs*') ? 'active' : '' }}" href="{{ url('user/logs') }}">
        <i class="ni ni-ui-04"></i>
        <span class="nav-link-text">{{ __('Message Reports') }}</span>
      </a>
    </li>
  @endif
</ul>



<!-- Divider -->
<hr class="my-3 mt-6">
<!-- Heading -->
<h6 class="navbar-heading p-0 text-muted">{{ __('Settings') }}</h6>
<!-- Navigation -->
<ul class="navbar-nav mb-md-3">
    @if(!Auth::user()->team_id)
  <li class="nav-item">
    <a class="nav-link {{ Request::is('user/subscription*') ? 'active' : '' }}" href="{{ url('/user/subscription') }}">
      <i class="ni ni-spaceship"></i>
      <span class="nav-link-text">{{ __('Subscription') }}</span>
    </a>
  </li>
  @endif
  <li class="nav-item">
    <a class="nav-link {{ Request::is('user/support*') ? 'active' : '' }}" href="{{ url('/user/support') }}" >
      <i class="fas fa-headset"></i>
      <span class="nav-link-text">{{ __('Help & Support') }}</span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="{{ url('/user/profile') }}">
      <i class="ni ni-settings-gear-65"></i>
      <span class="nav-link-text">{{ __('Profile Settings') }}</span>
    </a>
  </li>
  @if(!Auth::user()->team_id)
   <li class="nav-item">
    <a class="nav-link {{ Request::is('user/auth-key*') ? 'active' : '' }}" href="{{ url('/user/auth-key') }}">
      <i class="ni ni-key-25"></i>
      <span class="nav-link-text">{{ __('Auth Key') }}</span>
    </a>
  </li>
  @endif
  
  <li class="nav-item">
    <a class="nav-link logout-button" href="#" >
      <i class="ni ni-button-power"></i>
      <span class="nav-link-text">{{ __('Logout') }}</span>
    </a>
  </li>
</ul>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Assuming 'cloudapi' is the key you want to check in localStorage
    const storedValue = localStorage.getItem('cloudapi');

    // Check if the key is present in localStorage
    if (storedValue !== null) {
        const cleanedValue = storedValue.replace(/^"(.*)"$/, '$1');
        // Assuming you want to append the retrieved value to the URL
        const whatsappLink = document.getElementById('whatsappLink');
        whatsappLink.href = `/user/cloudapi/chats/${cleanedValue}`;

        // Display the navigation menu item
        document.getElementById('whatsappNavItem').style.display = 'block';
    }
});
</script>
