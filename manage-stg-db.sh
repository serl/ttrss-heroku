#!/bin/bash

STG_REMOTE="staging"
PRD_REMOTE="production"

get_app_name () {
    local url
    url_regex="https://git\.heroku\.com/(.+)\.git"
    url="$(git remote get-url "$1")"
    [[ $url =~ $url_regex ]] && echo "${BASH_REMATCH[1]}"
}

STG_APP="$(get_app_name "$STG_REMOTE")"
PRD_APP="$(get_app_name "$PRD_REMOTE")"
CMD="$1"

if ! [[ $STG_APP ]] || ! [[ $PRD_APP ]]; then
    CMD="help"
fi

case "$CMD" in
    reset)
        heroku pg:reset DATABASE_URL --app "$STG_APP" --confirm "$STG_APP"
        heroku restart --app "$STG_APP"
        ;;
    clone)
        heroku pg:copy "$PRD_APP"::DATABASE_URL DATABASE_URL --app "$STG_APP" --confirm "$STG_APP"
        heroku restart --app "$STG_APP"
        ;;
    *)
        echo "Usage: $0 <reset|clone>"
        echo "Note: '$STG_REMOTE' and '$PRD_REMOTE' remotes must exist in your git repository and point to Heroku applications."
        exit 1
        ;;
esac
