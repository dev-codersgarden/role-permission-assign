
import { ref } from "vue";


export function role() {

  const userPermissions = ref(page.props.auth.permissions);

  const checkPermission = (route) => {
    // return true;
    const hasValue = Object.values(userPermissions.value).includes(route);
    return hasValue;
  };
}

return {
    checkPermission,
};
