import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

export function can(ability) {
    if (ability == 'All') {
        return true;
    }

    const user = computed(() => usePage().props.auth.user)
    const permissions = computed(() => usePage().props.auth.permissions)

    if (permissions.value.includes('can-all')) {
        return true;
    }

    if (!user.value || !permissions.value.length) {
        return false
    }

    if (!ability) {
        return false
    }

    return permissions.value.includes(ability)

}

export function badgeColor(status) {
    let color = 'grey-7'
    switch (status.toLowerCase()) {
        case 'processed':
            color = 'amber-9'
            break;
        case 'shipped':
            color = 'teal'
            break;
        case 'completed':
            color = 'green'
            break;
    }
    return color
}

export function dateFormat (date) {
   if (!date) return ''
   const d = new Date(date);

   let options = {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
      // second: 'numeric',
      // timeZoneName: 'short',
   };

   return new Intl.DateTimeFormat('id', options).format(d);

}

