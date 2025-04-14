if (document.readyState && document.readyState !== 'loading')
{
  documentReady();
} else
{
  document.addEventListener('DOMContentLoaded', async () => await documentReady(), false);
}

async function documentReady()
{
  var readeckButtons = document.querySelectorAll('#stream .flux a.readeckButton');
  for (var i = 0; i < readeckButtons.length; i++)
  {
    let readeckButton = readeckButtons[i];
    readeckButton.addEventListener('click', async function (e)
    {
      if (!readeckButton)
      {
        return;
      }

      var active = readeckButton.closest(".flux");
      if (!active)
      {
        return;
      }

      e.preventDefault();
      e.stopPropagation();

      await add_to_readeck(readeckButton, active);
    }, false);
  }

  if (readeck_button_vars.keyboard_shortcut)
  {
    document.addEventListener('keydown', function (e)
    {
      if (e.ctrlKey || e.metaKey || e.altKey || e.shiftKey || e.target.closest('input, textarea'))
      {
        return;
      }

      if (e.key === readeck_button_vars.keyboard_shortcut)
      {
        var active = document.querySelector("#stream .flux.active");
        if (!active)
        {
          return;
        }

        var readeckButton = active.querySelector("a.readeckButton");
        if (!readeckButton)
        {
          return;
        }

        add_to_readeck(readeckButton, active);
      }
    });
  }
}

function requestFailed(activeId, readeckButtonImg, loadingAnimation)
{
  delete pending_entries[activeId];

  readeckButtonImg.classList.remove("disabled");
  loadingAnimation.classList.add("disabled");

  badAjax(this.status == 403);
}

async function add_to_readeck(readeckButton, active)
{
  const url = readeckButton.getAttribute("href");
  if (!url)
  {
    return;
  }

  let readeckButtonImg = readeckButton.querySelector("img");
  readeckButtonImg.classList.add("disabled");

  let loadingAnimation = readeckButton.querySelector(".lds-dual-ring");
  loadingAnimation.classList.remove("disabled");

  let activeId = active.getAttribute('id');
  if (pending_entries[activeId])
  {
    return;
  }

  pending_entries[activeId] = true;
  await fetch(url,
    {
      method: "POST",
      headers:
      {
        "Content-Type": "application/json",
        "Accept": "application/json",
      },
      body: JSON.stringify({
        _csrf: context.csrf,
      })
    })
    .then(async response =>
    {
      delete pending_entries[activeId];

      readeckButtonImg.classList.remove("disabled");
      loadingAnimation.classList.add("disabled");

      if (!response.ok)
      {
        requestFailed(activeId, readeckButtonImg, loadingAnimation);
        openNotification(readeck_button_vars.i18n.failed_to_add_article_to_readeck.replace('%s', json.errorCode), 'readeck_button_bad');
        return;
      }

      let json = await response.json();
      if (!json)
      {
        requestFailed(activeId, readeckButtonImg, loadingAnimation);
        openNotification(readeck_button_vars.i18n.failed_to_add_article_to_readeck.replace('%s', json.errorCode), 'readeck_button_bad');
        return;
      }

      console.log(readeck_button_vars);
      console.log(json.errorCode);

      switch (json.errorCode)
      {
        case 200:
        case 201:
        case 202:
        case 301:
          readeckButtonImg.setAttribute("src", readeck_button_vars.icons.added_to_readeck);
          openNotification(readeck_button_vars.i18n.added_article_to_readeck.replace('%s', json.response.title), 'readeck_button_good');
          break;

        case 401:
          openNotification(readeck_button_vars.i18n.relog_required, 'readeck_button_bad');
          break;

        case 404:
          openNotification(readeck_button_vars.i18n.article_not_found, 'readeck_button_bad');
          break;

        case 500:
          openNotification(readeck_button_vars.i18n.failed_to_add_article_to_readeck, 'readeck_button_bad');
          break;

        default:
          requestFailed(activeId, readeckButtonImg, loadingAnimation);
          break;
      }
    });
}
